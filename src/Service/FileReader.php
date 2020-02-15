<?php

namespace App\Service;

class FileReader
{
    /**
     * @var resource
     */
    private $fileHandle;

    public function openFile(string $filename): void
    {
        if (! $this->fileHandle = fopen($filename, 'r')) {
            trigger_error('There was an error attempting to open the file.');
        }
    }

    public function readLine(): string
    {
        return fgets($this->fileHandle);
    }

    public function closeFile(): void
    {
        fclose($this->fileHandle);
    }
}
