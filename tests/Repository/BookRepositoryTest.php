<?php

namespace App\Tests\Repository;

use App\Tests\Functional\FunctionalTestCase;

class BookRepositoryTest extends FunctionalTestCase
{
    public function testFindOneByTitleString()
    {
        $book = $this->bookRepository
            ->findOneByTitleString('Test title');
        $this->assertNotNull($book);
        $this->assertEquals('Jim', $book->getAuthorFirstName());
    }
}