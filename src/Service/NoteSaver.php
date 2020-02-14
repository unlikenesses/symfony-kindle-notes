<?php

namespace App\Service;

use DateTime;
use App\Entity\Book;
use App\Entity\Note;
use App\ValueObject\Book as BookObject;
use App\ValueObject\Note as NoteObject;
use App\Repository\BookRepository;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

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

    /**
     * @var Security
     */
    private $security;

    public function __construct(EntityManagerInterface $entityManager, BookRepository $bookRepository, NoteRepository $noteRepository, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->bookRepository = $bookRepository;
        $this->noteRepository = $noteRepository;
        $this->security = $security;
    }

    public function storeNotes(array $parsedBooks): void
    {
        foreach ($parsedBooks as $book) {
            $this->storeBookNotes($book);
        }
    }

    private function storeBookNotes(BookObject $book): array
    {
        $newBook = false;
        $numNotes = 0;
        $metadata = $book->getMetadata();
        $bookToAdd = $this->bookRepository->findOneByTitleString($metadata['titleString']);
        if (!$bookToAdd) {
            $newBook = true;
            $bookToAdd = $this->addBook($metadata);
        }

        foreach ($book->getNotes() as $note) {
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
            ->setAuthorLastName($metadata['lastName'])
            ->setUser($this->security->getUser());
        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $book;
    }

    private function saveImportedNote(NoteObject $note, Book $book): bool
    {
        if ($note->getHighlight() &&
            $note->getMeta() &&
            ! $this->noteRepository->findNoteInBook($note->getMeta(), $book)) {
            $date = '';
            $page = '';
            $location = '';
            $noteText = '';
            $noteType = 0;
            $noteMetadata = $note->getMeta();

            if (isset($noteMetadata['date'])) {
                $date = new DateTime($noteMetadata['date']);
            }

            if (isset($noteMetadata['page'])) {
                $page = $noteMetadata['page'];
            }

            if (isset($noteMetadata['location'])) {
                $location = $noteMetadata['location'];
            }

            if ($note->getHighlight()) {
                $noteText = $note->getHighlight();
            }

            if ($note->getType()) {
                $noteType = $note->getType();
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
