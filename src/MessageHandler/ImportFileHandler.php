<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\ImportFile;
use App\Service\FileHandler;
use App\Service\NoteSaver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ImportFileHandler implements MessageHandlerInterface
{
    private $fileHandler;
    private $noteSaver;
    private $em;

    public function __construct(FileHandler $fileHandler, NoteSaver $noteSaver, EntityManagerInterface $em)
    {
        $this->fileHandler = $fileHandler;
        $this->noteSaver = $noteSaver;
        $this->em = $em;
    }

    public function __invoke(ImportFile $importFile)
    {
        $fileToImport = $importFile->getFileToImport();
        $books = $this->fileHandler->processFile($fileToImport);
        $this->noteSaver->storeNotes($books, $fileToImport->getUserId());
    }
}