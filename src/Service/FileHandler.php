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
     * @var Parser
     */
    private $parser;

    public function __construct(FileUploader $uploader, Parser $parser)
    {
        $this->uploader = $uploader;
        $this->parser = $parser;
    }

    public function handleFile(UploadedFile $file, string $uploadsDir)
    {
        $filename = $this->uploader->upload($file);
        return $this->parser->parseFile($uploadsDir . '/' . $filename);
    }
}