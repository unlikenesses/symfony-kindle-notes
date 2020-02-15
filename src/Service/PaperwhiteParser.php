<?php

namespace App\Service;

use App\ValueObject\Book;
use App\ValueObject\Note;
use App\ValueObject\Author;
use App\ValueObject\FileLine;
use App\ValueObject\BookList;
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
            // First line of a note, i.e. the title
            $title = $line->getLine();
            $this->bookList->toggleInBook();
            $this->book = $this->createOrAssignBook($title);
            $this->note = new Note();
            return;
        }
        if ($line->equals(self::SEPARATOR)) {
            // End of a note.
            if ($this->bookList->getPos() < 0) {
                $this->bookList->addBook($this->book);
            } else {
                $this->bookList->updateBook($this->book);
            }
            $this->bookList->toggleInBook();
        } elseif ($line->contains(self::HIGHLIGHT_STRING)) {
            $this->note->setMeta($this->parseMeta($line->getLine()));
            $this->note->setType(1);
        } elseif ($line->contains(self::NOTE_STRING)) {
            $this->note->setMeta($this->parseMeta($line->getLine()));
            $this->note->setType(2);
        } else {
            $this->note->setHighlight($line->getLine());
            $this->book->addNote($this->note);
        }
    }

    private function createOrAssignBook(string $titleString): Book
    {
        $book = $this->bookList->findBookByTitleString($titleString);
        if (! $book) {
            $parsedTitle = $this->parseTitleString($titleString);
            $book = new Book([
                'titleString' => $titleString,
                'title' => $parsedTitle['title'],
                'author' => $parsedTitle['author'],
            ]);
        }

        return $book;
    }

    public function parseTitleString(string $titleString): array
    {
        /*
            The idea is to split the title field into title string + author string.
            Based on my sample size of 27, authors are typically separated by a hyphen or brackets.
            Brackets are more common.
            Title strings can contain hyphens AND brackets. E.g. a hyphen for a date range, then author in brackets.
            Title strings can also contain more than 1 instance of the separator used to designate the author:
            e.g. if the author separator is a hyphen, there may be more than 1 hyphen ("Century of Revolution, 1603-1714 - Christopher Hill").
            e.g. same for brackets ("Rights of War and Peace (2005 ed.) vol. 1 (Book I) (Hugo Grotius)").
            So we take the last instance of the separator as the author.
            This will fail in some instances: e.g. "Harvey, David - A brief history of neoliberalism", where the author comes before the title.
            But this seems to be an exception.
        */

        $author = '';
        $title = '';

        // Check if the title ends with a closing bracket:
        if (substr($titleString, -1) === ')') {
            preg_match('/\(([^)]*)\)[^(]*$/', $titleString, $output);
            $author = $output[sizeof($output) - 1];
            $title = trim(str_replace('(' . $author . ')', '', $titleString));
        } else {
            /*
                Check if there's a hyphen separated by spaces:
                Don't bother if there's more than one instance, this is too hard to parse.
            */
            if (substr_count($titleString, ' - ') === 1) {
                list($partOne, $partTwo) = explode(' - ', $titleString);
                /*
                    Now the problem here is that either part could be the author's name.
                    For now we have to assume it's part two, and leave it to the user to correct if not.
                    I think Calibre does that too.
                    Maybe later check against a list of common names, e.g. https://github.com/hadley/data-baby-names
                */
                $author = $partTwo;
                $title = trim($partOne);
            }
        }
        if ($author !== '') {
            $parsedAuthor = $this->parseAuthor($author);
        } else {
            throw new \Exception('Could not parse author: ' . $titleString);
        }

        return [
            'title' => $title,
            'author' => $parsedAuthor,
        ];
    }

    public function parseAuthor(string $author): Author
    {
        $author = trim($author);

        // Do we have a [last name, first name] format?
        if (strpos($author, ',') !== false) {
            list($lastName, $firstName) = explode(',', $author);
        } else {
            // Use a space:
            $nameArray = explode(' ', $author);
            $lastName = $nameArray[sizeof($nameArray) - 1];
            array_pop($nameArray);
            $firstName = implode(' ', $nameArray);
        }

        return new Author($firstName, $lastName);
    }

    public function parseMeta(string $meta): NoteMetadata
    {
        $noteMetadata = new NoteMetadata();

        if (stristr($meta, 'page')) {
            preg_match("/page (\d*-?\d*)/", $meta, $output);
            $noteMetadata->setPage($output[1]);
        }

        if (stristr($meta, 'location')) {
            preg_match("/location (\d*-?\d*)/", $meta, $output);
            $noteMetadata->setLocation($output[1]);
        }

        if (stristr($meta, 'added')) {
            preg_match("/Added on (.*)/", $meta, $output);
            $noteMetadata->setDate($output[1]);
        }

        return $noteMetadata;
    }

    public function getBookList(): BookList
    {
        return $this->bookList;
    }
}
