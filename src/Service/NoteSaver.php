<?php

namespace App\Service;

use DateTime;
use App\Entity\User;
use App\Entity\Book;
use App\Entity\Note;
use App\ValueObject\Book as BookObject;
use App\ValueObject\Note as NoteObject;
use App\ValueObject\Author as AuthorObject;
use App\ValueObject\BookList as BookListObject;
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

    public function storeNotes(BookListObject $parsedBooks, int $userId): void
    {
        foreach ($parsedBooks->getList() as $book) {
            $this->storeBookNotes($book, $userId);
        }
    }

    private function storeBookNotes(BookObject $book, int $userId): array
    {
        $newBook = false;
        $numNotes = 0;
        $metadata = $book->getMetadata();
        $bookToAdd = $this->bookRepository->findOneByTitleString($metadata['titleString']);
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($userId);
        if (!$bookToAdd) {
            $newBook = true;
            $bookToAdd = $this->addBook($metadata, $user);
        }

        foreach ($book->getNotes() as $note) {
            if ($this->saveImportedNote($note, $bookToAdd, $user)) {
                $numNotes++;
            }
        }

        return ['newBook' => $newBook, 'numNotes' => $numNotes];
    }

    private function addBook(array $metadata, User $user): Book
    {
        /** @var AuthorObject $author */
        $author = $metadata['author'];
        $book = new Book();
        $book->setTitleString($metadata['titleString'])
            ->setTitle($metadata['title'])
            ->setAuthorFirstName($author->getFirstName())
            ->setAuthorLastName($author->getLastName())
            ->setUser($user);
        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $book;
    }

    private function saveImportedNote(NoteObject $note, Book $book, User $user): bool
    {
        if ($note->getText() &&
            $note->getMetadata() &&
            ! $this->noteRepository->findNoteInBook($note->getMetadata(), $book)) {
            $date = '';
            $page = '';
            $location = '';
            $noteText = '';
            $noteType = 0;
            $noteMetadata = $note->getMetadata();

            if ($noteMetadata->getDate()) {
                $date = new DateTime($noteMetadata->getDate());
            }

            if ($noteMetadata->getPage()) {
                $page = $noteMetadata->getPage();
            }

            if ($noteMetadata->getLocation()) {
                $location = $noteMetadata->getLocation();
            }

            if ($note->getText()) {
                $noteText = $note->getText();
            }

            if ($note->getType()) {
                $noteType = $note->getType();
            }

            $hash = md5($page.$location.$date->format('U').$noteText);

            $newNote = new Note();
            $newNote->setDate($date)
                ->setPage($page)
                ->setLocation($location)
                ->setNote($noteText)
                ->setType($noteType)
                ->setBook($book)
                ->setHash($hash)
                ->setUser($user);
            $this->entityManager->persist($newNote);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }
}
