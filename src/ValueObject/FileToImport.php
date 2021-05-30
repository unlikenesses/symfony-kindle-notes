<?php

namespace App\ValueObject;

use App\Entity\User;

class FileToImport
{
    private $filename;
    private $userId;

    public function __construct(string $filename, User $user)
    {
        $this->filename = $filename;
        $this->userId = $user->getId();
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}