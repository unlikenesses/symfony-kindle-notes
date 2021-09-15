<?php

namespace App\Tests\Functional\API;

use App\Tests\Functional\FunctionalTestCase;

class BookAPITest extends FunctionalTestCase
{
    use HasAPIInteraction;

    public function testDeletingABookSoftDeletesIt()
    {
        $this->loginUser();
        // Get books
        $books = $this->getBooks();
        $this->assertCount(2, $books);
        // Delete a book
        $bookToDelete = $books[0]->id;
        $response = $this->deleteBook($bookToDelete);
        $this->assertSame('success', $response->message);
        // Check it is not returned by the API
        $books = $this->getBooks();
        $this->assertCount(1, $books);
        // Check the book still exists in the database
        $book = $this->bookRepository->find($bookToDelete);
        $this->assertNotNull($book);
        // Check its softDeleted column is set
        $this->assertNotNull($book->getDeletedAt());
    }
}