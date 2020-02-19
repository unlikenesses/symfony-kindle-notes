<?php

namespace App\Service\Parser\Paperwhite;

use App\ValueObject\Author;
use App\Exception\ParseAuthorException;
use App\Service\Parser\TitleStringParserInterface;

class PaperwhiteTitleStringParser implements TitleStringParserInterface
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var author
     */
    private $author;

    public function parse(string $titleString): void
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
            $this->title = trim(str_replace('(' . $author . ')', '', $titleString));
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
                $this->title = trim($partOne);
            }
        }
        if ($author !== '') {
            $this->author = $this->parseAuthor($author);
        } else {
            throw new ParseAuthorException();
        }
    }

    private function parseAuthor(string $author): Author
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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }
}