<?php

namespace App\Service\Parser\Paperwhite;

use App\ValueObject\Book;
use App\ValueObject\Note;
use App\ValueObject\FileLine;
use App\ValueObject\BookList;
use App\ValueObject\TitleString;
use App\ValueObject\NoteMetadata;
use App\Service\Parser\ParserInterface;

class PaperwhiteParser implements ParserInterface
{
    const SEPARATOR = '==========';
    const HIGHLIGHT_STRING = 'your highlight';
    const NOTE_STRING = 'your note';

    /**
     * @var Book
     */
    private $book;

    /**
     * @var BookList
     */
    private $bookList;

    /**
     * @var Note
     */
    private $note;

    public function __construct()
    {
        $this->bookList = new BookList();
    }

    public function parseLine(FileLine $line): void
    {
        if ($line->isEmpty()) {
            return;
        }
        if (! $this->bookList->getInNote()) {
            $this->initialiseNote($line->getLine());
        } elseif ($line->equals(self::SEPARATOR)) {
            $this->bookList->completeNote($this->book);
        } elseif ($line->contains(self::HIGHLIGHT_STRING)) {
            $noteMetadata = new NoteMetadata($line->getLine());
            $this->note->setMetadata($noteMetadata);
            $this->note->setType(1);
        } elseif ($line->contains(self::NOTE_STRING)) {
            $noteMetadata = new NoteMetadata($line->getLine());
            $this->note->setMetadata($noteMetadata);
            $this->note->setType(2);
        } else {
            $this->note->setHighlight($line->getLine());
            $this->book->addNote($this->note);
        }
    }

    private function initialiseNote(string $title): void
    {
        $this->book = $this->createOrAssignBook($title);
        $this->note = new Note();
    }

    private function createOrAssignBook(string $titleString): Book
    {
        $book = $this->bookList->findBookByTitleString($titleString);
        if (! $book) {
            $titleStringObject = new TitleString($titleString);
            $parsedTitle = $titleStringObject->parse();
            $book = new Book([
                'titleString' => $titleString,
                'title' => $parsedTitle['title'],
                'author' => $parsedTitle['author'],
            ]);
        }

        return $book;
    }

    public function getBookList(): BookList
    {
        return $this->bookList;
    }
}
