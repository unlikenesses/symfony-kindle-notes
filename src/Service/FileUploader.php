<?php

namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /**
     * @var string
     */
    private $destination;

    public function __construct(string $destination)
    {
        $this->destination = $destination;
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

        return $newFilename;
    }
}