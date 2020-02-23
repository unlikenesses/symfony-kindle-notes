<?php

namespace App\Service;

use App\ValueObject\BookList;
use App\ValueObject\FileLine;
use App\Service\Parser\LineReaderInterface;
use App\Service\Parser\Actions\ActionInterface;
use App\Service\Parser\Actions\InitialiseNoteAction;
use App\Service\Parser\TitleStringParserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileHandler
{
    /**
     * @var FileUploader
     */
    private $uploader;

    /**
     * @var FileReader
     */
    private $fileReader;

    /**
     * @var LineReaderInterface
     */
    private $lineReader;

    /**
     * @var BookList
     */
    private $bookList;

    /**
     * @var TitleStringParserInterface
     */
    private $titleStringParser;

    public function __construct(FileUploader $uploader, FileReader $fileReader, LineReaderInterface $lineReader, TitleStringParserInterface $titleStringParser)
    {
        $this->bookList = new BookList();
        $this->uploader = $uploader;
        $this->fileReader = $fileReader;
        $this->lineReader = $lineReader;
        $this->titleStringParser = $titleStringParser;
    }

    public function handleFile(UploadedFile $file): BookList
    {
        $filename = $this->uploader->upload($file);
        $this->readFile($filename);

        return $this->bookList;
    }

    private function readFile(string $filename): void
    {
        $this->fileReader->openFile($filename);
        while ($line = $this->fileReader->readLine()) {
            $action = $this->getAction(new FileLine($line));
            $this->bookList = $action->execute($this->bookList);
        }
        $this->fileReader->closeFile();
    }

    private function getAction(FileLine $line): ActionInterface
    {
        if ($this->bookList->getInNote()) {
            return $this->lineReader->classifyLine($line);
        }

        return new InitialiseNoteAction($line->getLine(), $this->titleStringParser);
    }
}
