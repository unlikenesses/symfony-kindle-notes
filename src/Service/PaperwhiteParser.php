<?php

namespace App\Service;

use App\ValueObject\Book;
use App\ValueObject\Note;
use App\ValueObject\FileLine;
use App\ValueObject\BookList;
use App\ValueObject\TitleString;
use App\ValueObject\NoteMetadata;

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
        if (! $this->bookList->getInBook()) {
            $this->initialiseBook($line->getLine());
        } elseif ($line->equals(self::SEPARATOR)) {
            $this->bookList->completeNote($this->book);
        } elseif ($line->contains(self::HIGHLIGHT_STRING)) {
            $this->note->setMeta($this->parseNoteMetadata($line->getLine()));
            $this->note->setType(1);
        } elseif ($line->contains(self::NOTE_STRING)) {
            $this->note->setMeta($this->parseNoteMetadata($line->getLine()));
            $this->note->setType(2);
        } else {
            $this->note->setHighlight($line->getLine());
            $this->book->addNote($this->note);
        }
    }

    private function initialiseBook(string $title): void
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

    public function parseNoteMetadata(string $metadata): NoteMetadata
    {
        $noteMetadata = new NoteMetadata();

        if (stristr($metadata, 'page')) {
            preg_match("/page (\d*-?\d*)/", $metadata, $output);
            $noteMetadata->setPage($output[1]);
        }

        if (stristr($metadata, 'location')) {
            preg_match("/location (\d*-?\d*)/", $metadata, $output);
            $noteMetadata->setLocation($output[1]);
        }

        if (stristr($metadata, 'added')) {
            preg_match("/Added on (.*)/", $metadata, $output);
            $noteMetadata->setDate($output[1]);
        }

        return $noteMetadata;
    }

    public function getBookList(): BookList
    {
        return $this->bookList;
    }
}
