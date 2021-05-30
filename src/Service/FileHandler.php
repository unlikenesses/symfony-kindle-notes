<?php

namespace App\Service;

use App\ValueObject\BookList;
use App\ValueObject\FileLine;
use App\Service\Parser\LineReaderInterface;
use App\Service\Parser\Actions\ActionInterface;
use App\Service\Parser\Actions\InitialiseNoteAction;
use App\Service\Parser\TitleStringParserInterface;
use App\ValueObject\FileToImport;

class FileHandler
{
    private $fileReader;
    private $lineReader;
    private $bookList;
    private $titleStringParser;

    public function __construct(FileReader $fileReader, LineReaderInterface $lineReader, TitleStringParserInterface $titleStringParser)
    {
        $this->bookList = new BookList();
        $this->fileReader = $fileReader;
        $this->lineReader = $lineReader;
        $this->titleStringParser = $titleStringParser;
    }

    public function processFile(FileToImport $fileToImport): BookList
    {
        foreach ($this->readFile($fileToImport->getFilename()) as $line) {
            $action = $this->getAction(new FileLine($line));
            $this->bookList = $action->execute($this->bookList);
        }

        return $this->bookList;
    }

    private function readFile(string $filename): iterable
    {
        $this->fileReader->openFile($filename);
        while ($line = $this->fileReader->readLine()) {
            yield $line;
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
