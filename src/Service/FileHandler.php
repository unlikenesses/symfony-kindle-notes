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

        return $this->parser->parseFile($filename);
    }
}