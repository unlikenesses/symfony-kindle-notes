<?php


namespace App\Service;

class FileReader
{
    /**
     * @var false|resource
     */
    private $fileHandle;

    public function openFile(string $filename): void
    {
        try {
            $this->fileHandle = fopen($filename, 'r');
        } catch (\Exception $e) {
            dd($e); // TODO: proper error handling
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
