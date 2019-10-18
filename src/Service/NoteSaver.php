<?php

namespace App\Service;

use DateTime;
use App\Entity\Book;
use App\Entity\Note;
use App\Repository\BookRepository;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;

class NoteSaver
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var BookRepository
     */
    private $bookRepository;

    /**
     * @var NoteRepository
     */
    private $noteRepository;

    public function __construct(EntityManagerInterface $entityManager, BookRepository $bookRepository, NoteRepository $noteRepository)
    {
        $this->entityManager = $entityManager;
        $this->bookRepository = $bookRepository;
        $this->noteRepository = $noteRepository;
    }

    public function storeNotes(array $parsedBooks): void
    {
        foreach ($parsedBooks as $book) {
            $this->storeBookNotes($book);
        }
    }

    private function storeBookNotes(array $book): array
    {
        $newBook = false;
        $numNotes = 0;
        $bookToAdd = $this->bookRepository->findOneByTitleString($book['metadata']['titleString']);
        if (!$bookToAdd) {
            $newBook = true;
            $bookToAdd = $this->addBook($book['metadata']);
        }

        foreach ($book['notes'] as $note) {
            if ($this->saveImportedNote($note, $bookToAdd)) {
                $numNotes++;
            }
        }

        return ['newBook' => $newBook, 'numNotes' => $numNotes];
    }

    private function addBook(array $metadata): Book
    {
        $book = new Book();
        $book->setTitleString($metadata['titleString'])
            ->setTitle($metadata['title'])
            ->setAuthorFirstName($metadata['firstName'])
            ->setAuthorLastName($metadata['lastName']);
        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $book;
    }

    private function saveImportedNote(array $note, Book $book): bool
    {
        if (isset($note['highlight']) &&
            isset($note['meta']) &&
            ! $this->noteRepository->findNoteInBook($note, $book)) {
            $date = '';
            $page = '';
            $location = '';
            $noteText = '';
            $noteType = 0;

            if (isset($note['meta']['date'])) {
                $date = new DateTime($note['meta']['date']);
            }

            if (isset($note['meta']['page'])) {
                $page = $note['meta']['page'];
            }

            if (isset($note['meta']['location'])) {
                $location = $note['meta']['location'];
            }

            if (isset($note['highlight'])) {
                $noteText = trim($note['highlight']);
            }

            if (isset($note['type'])) {
                $noteType = trim($note['type']);
            }

            $newNote = new Note();
            $newNote->setDate($date)
                ->setPage($page)
                ->setLocation($location)
                ->setNote($noteText)
                ->setType($noteType)
                ->setBook($book);
            $this->entityManager->persist($newNote);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }
}