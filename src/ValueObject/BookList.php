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
    private $inBook = false;

    public function getPos(): int
    {
        return $this->pos;
    }

    public function getList(): array
    {
        return $this->list;
    }

    public function getInBook(): bool
    {
        return $this->inBook;
    }

    public function toggleInBook(): void
    {
        $this->inBook = ! $this->inBook;
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
        $this->inBook = false;
    }

    public function findBookByTitleString(string $titleString): ?Book
    {
        $this->pos = -1;
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