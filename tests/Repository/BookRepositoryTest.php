<?php

namespace App\Tests\Repository;

use App\DataFixtures\BookNoteFixtures;
use App\Tests\Functional\FunctionalTestCase;

class BookRepositoryTest extends FunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([
            BookNoteFixtures::class,
        ]);
    }

    public function testFindOneByTitleString()
    {
        $book = $this->bookRepository
            ->findOneByTitleString('Test title');
        $this->assertNotNull($book);
        $this->assertEquals('Jim', $book->getAuthorFirstName());
    }
}