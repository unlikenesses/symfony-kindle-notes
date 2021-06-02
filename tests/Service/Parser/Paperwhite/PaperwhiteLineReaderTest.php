<?php

namespace App\Tests\Service\Parser\Paperwhite;

use App\ValueObject\FileLine;
use App\Service\Parser\Actions\EmptyAction;
use App\Service\Parser\Actions\CreateNoteAction;
use App\Service\Parser\Actions\UpdateNoteAction;
use App\Service\Parser\Actions\CompleteNoteAction;
use App\Service\Parser\Actions\CreateHighlightAction;
use App\Service\Parser\Paperwhite\PaperwhiteLineReader;
use PHPUnit\Framework\TestCase;

class PaperwhiteLineReaderTest extends TestCase
{
    /**
     * @var PaperwhiteLineReader
     */
    private $lineReader;

    public function setUp(): void
    {
        $this->lineReader = new PaperwhiteLineReader();
    }

    /**
     * @dataProvider getLineTests
     */
    public function testItCanClassifyALine(string $line, string $expectedClass)
    {
        $actionClass = $this->lineReader->classifyLine(new FileLine($line));
        $this->assertInstanceOf($expectedClass, $actionClass);
    }

    public function getLineTests()
    {
        return [
            // line, action-class
            [
                '',
                EmptyAction::class,
            ],
            [
                '==========',
                CompleteNoteAction::class,
            ],
            [
                '- Your Highlight at location 260-262 | Added on Saturday, 25 April 2015 09:21:07',
                CreateHighlightAction::class,
            ],
            [
                '- Your Note on page 116 | location 1776 | Added on Saturday, 15 October 2016 14:02:31',
                CreateNoteAction::class,
            ],
            [
                'Here is some random text.',
                UpdateNoteAction::class,
            ],
        ];
    }
}