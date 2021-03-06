<?php

namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploader
{
    /**
     * @var string
     */
    private $destination;

    public function __construct(string $clippingsDirectory)
    {
        $this->destination = $clippingsDirectory;
    }

    public function upload(UploadedFile $uploadedFile): string
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
        // TODO: Validate
        // TODO: FlySystem?
        // TODO: Use private location
        try {
            $uploadedFile->move($this->destination, $newFilename);
        } catch (FileException $e) {
            // TODO
        }

        return $this->destination . '/' . $newFilename;
    }
}
