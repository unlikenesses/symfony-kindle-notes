<?php

namespace App\Service;

use App\ValueObject\BookList;
use App\ValueObject\FileLine;
use App\Service\Parser\LineReaderInterface;
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

    public function __construct(FileUploader $uploader, FileReader $fileReader, LineReaderInterface $lineReader)
    {
        $this->uploader = $uploader;
        $this->fileReader = $fileReader;
        $this->lineReader = $lineReader;
    }

    public function handleFile(UploadedFile $file): BookList
    {
        $filename = $this->uploader->upload($file);
        $this->readFile($filename);

        return $this->lineReader->getBookList();
    }

    private function readFile(string $filename): void
    {
        $this->fileReader->openFile($filename);
        while ($line = $this->fileReader->readLine()) {
            $this->lineReader->parseLine(new FileLine($line));
        }
        $this->fileReader->closeFile();
    }
}
