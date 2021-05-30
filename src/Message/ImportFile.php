<?php

namespace App\Message;

use App\ValueObject\FileToImport;

class ImportFile
{
    private $fileToImport;

    public function __construct(FileToImport $fileToImport)
    {
        $this->fileToImport = $fileToImport;
    }

    public function getFileToImport(): FileToImport
    {
        return $this->fileToImport;
    }
}