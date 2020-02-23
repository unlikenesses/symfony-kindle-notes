<?php

namespace App\Service\Parser\Actions;

use App\Service\Parser\TitleStringParserInterface;
use App\ValueObject\Book;
use App\ValueObject\BookList;
use App\Exception\ParseAuthorException;
use App\Service\Parser\Paperwhite\PaperwhiteTitleStringParser;

class InitialiseNoteAction implements ActionInterface
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var TitleStringParserInterface
     */
    private $titleStringParser;

    public function __construct(string $title, TitleStringParserInterface $titleStringParser)
    {
        $this->title = $title;
        $this->titleStringParser = $titleStringParser;
    }

    public function execute(BookList $bookList): BookList
    {
        $book = $bookList->findBookByTitleString($this->title);
        if (! $book) {
            try {
                $this->titleStringParser->parse($this->title);
            } catch (ParseAuthorException $e) {
                trigger_error($e->getMessage());
            }
            $bookList->addBook(new Book([
                'titleString' => $this->title,
                'title' => $this->titleStringParser->getTitle(),
                'author' => $this->titleStringParser->getAuthor(),
            ]));
        }

        return $bookList;
    }
}