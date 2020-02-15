<?php

namespace App\Service;

use App\ValueObject\FileLine;
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
     * @var ParserInterface
     */
    private $parser;

    public function __construct(FileUploader $uploader, FileReader $fileReader, ParserInterface $parser)
    {
        $this->uploader = $uploader;
        $this->fileReader = $fileReader;
        $this->parser = $parser;
    }

    public function handleFile(UploadedFile $file): array
    {
        $filename = $this->uploader->upload($file);
        $this->readFile($filename);

        return $this->parser->getBooks();
    }

    private function readFile(string $filename): void
    {
        $this->fileReader->openFile($filename);
        while ($line = $this->fileReader->readLine()) {
            $this->parser->parseLine(new FileLine($line));
        }
        $this->fileReader->closeFile();
    }
}
