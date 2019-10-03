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

    /**
     * @var string
     */
    private $clippingsDirectory;

    public function __construct(FileUploader $uploader, ParserInterface $parser, string $clippingsDirectory)
    {
        $this->uploader = $uploader;
        $this->parser = $parser;
        $this->clippingsDirectory = $clippingsDirectory;
    }

    public function handleFile(UploadedFile $file): array
    {
        $filename = $this->uploader->upload($file);

        return $this->parser->parseFile($this->clippingsDirectory . '/' . $filename);
    }
}