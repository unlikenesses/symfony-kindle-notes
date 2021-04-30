<?php

namespace App\Command;

use _HumbugBoxe5640220fe34\React\Http\Io\UploadedFile;
use App\Service\FileHandler;
use App\Service\NoteSaver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\File;

class ImportClippingsCommand extends Command
{
    protected static $defaultName = 'clippings:import';
    private $fileHandler;
    private $noteSaver;

    public function __construct(FileHandler $fileHandler, NoteSaver $noteSaver)
    {
        $this->fileHandler = $fileHandler;
        $this->noteSaver = $noteSaver;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Imports a clippings file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filename = 'MyClippings.txt';
        $output->writeln('Importing file ' . $filename . '...');
        $file = new File($filename);
        $output->writeln('Size: ' . $file->getSize() . ' bytes.');
        $books = $this->fileHandler->handleFile($file);
        $this->noteSaver->storeNotes($books);

        return 0;
    }
}