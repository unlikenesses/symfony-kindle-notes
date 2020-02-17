<?php

namespace App\ValueObject;

class BookList
{
    /**
     * @var int
     */
    private $pos;

    /**
     * @var array
     */
    private $list = [];

    /**
     * @var bool
     */
    private $inNote = false;

    public function getList(): array
    {
        return $this->list;
    }

    public function getInNote(): bool
    {
        return $this->inNote;
    }

    public function addBook(Book $book): void
    {
        $this->list[] = $book;
    }

    public function updateBook(Book $book): void
    {
        $this->list[$this->pos] = $book;
    }

    public function completeNote(Book $book): void
    {
        if ($this->pos < 0) {
            $this->addBook($book);
        } else {
            $this->updateBook($book);
        }
        $this->inNote = false;
    }

    public function findBookByTitleString(string $titleString): ?Book
    {
        $this->pos = -1;
        $this->inNote = true;
        foreach ($this->list as $pos => $book) {
            $metadata = $book->getMetadata();
            if ($metadata['titleString'] === $titleString) {
                $this->pos = $pos;
                return $book;
            }
        }

        return null;
    }
}