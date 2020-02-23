<?php

namespace App\ValueObject;

class BookList
{
    /**
     * @var array
     */
    private $list = [];

    /**
     * @var Book
     */
    private $currentBook;

    /**
     * @var Note
     */
    private $currentNote;

    public function getList(): array
    {
        return $this->list;
    }

    public function getInNote(): bool
    {
        return $this->currentBook !== null;
    }

    public function addBook(Book $book): void
    {
        $this->list[] = $book;
        $this->currentBook = $book;
    }

    public function addNote(Note $note): void
    {
        $this->currentNote = $note;
        $this->currentBook->addNote($note);
    }

    public function updateNote(string $text): void
    {
        $this->currentNote->setText($text);
    }

    public function completeNote(): void
    {
        $this->currentBook = null;
    }

    public function findBookByTitleString(string $titleString): ?Book
    {
        foreach ($this->list as $book) {
            $metadata = $book->getMetadata();
            if ($metadata['titleString'] === $titleString) {
                $this->currentBook = $book;
                return $book;
            }
        }

        return null;
    }
}