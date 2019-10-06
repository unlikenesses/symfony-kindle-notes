<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileHandler
{
    /**
     * @var FileUploader
     */
    private $uploader;

    /**
     * @var ParserInterface
     */
    private $parser;

    public function __construct(FileUploader $uploader, ParserInterface $parser)
    {
        $this->uploader = $uploader;
        $this->parser = $parser;
    }

    public function handleFile(UploadedFile $file): array
    {
        $filename = $this->uploader->upload($file);
        $parsedBooks = $this->parser->parseFile($filename);
        $this->storeNotes($parsedBooks);

        return $parsedBooks;
    }

    private function storeNotes(array $parsedBooks)
    {
        foreach ($parsedBooks as $book) {
            
        }
    }
}