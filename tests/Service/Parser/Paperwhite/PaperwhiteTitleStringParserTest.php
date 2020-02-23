<?php

namespace App\Tests\Service\Parser\Paperwhite;

use App\ValueObject\Author;
use App\Service\Parser\Paperwhite\PaperwhiteTitleStringParser;
use PHPUnit\Framework\TestCase;

class PaperwhiteTitleStringParserTest extends TestCase
{
    private $parser;

    public function setUp()
    {
        $this->parser = new PaperwhiteTitleStringParser();
    }

    /**
     * @dataProvider getTitleStringTests
     */
    public function testItCanParseATitleString(string $titleString, string $expectedTitle, Author $expectedAuthor)
    {
        $this->parser->parse($titleString);
        $this->assertEquals($expectedTitle, $this->parser->getTitle());
        $this->assertEquals($expectedAuthor, $this->parser->getAuthor());
    }

    public function getTitleStringTests()
    {
        return [
            // titleString, title, author
            [
                'Century of Revolution, 1603-1714 - Christopher Hill',
                'Century of Revolution, 1603-1714',
                new Author('Christopher', 'Hill'),
            ],
            [
                'Rights of War and Peace (2005 ed.) vol. 1 (Book I) (Hugo Grotius)',
                'Rights of War and Peace (2005 ed.) vol. 1 (Book I)',
                new Author('Hugo', 'Grotius'),
            ],
            [
                'Die Leiden des jungen Werther (Goethe, Johann Wolfgang von)',
                'Die Leiden des jungen Werther',
                new Author('Johann Wolfgang von', 'Goethe'),
            ],
            [
                'The Wisdom of Father Brown - Chesterton, G. K.',
                'The Wisdom of Father Brown',
                new Author('G. K.', 'Chesterton'),
            ],
        ];
    }
}