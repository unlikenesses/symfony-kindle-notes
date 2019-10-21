<?php

namespace App\Tests\Entity;

use App\Entity\Book;
use App\Tests\DatabaseTestCase;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    public function testSettingTitle()
    {
        $book = new Book();
        $testTitle = 'Test title';
        $book->setTitle($testTitle);
        $this->assertSame($testTitle, $book->getTitle());
    }
}
