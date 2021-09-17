<?php

namespace App\Tests\Functional\Traits;

trait HasAPIInteraction
{
    protected function getBooks(string $category = 'null')
    {
        $this->client->request('GET', '/api/books/' . $category);

        return $this->getData();
    }

    protected function deleteBook(int $bookId)
    {
        $this->client->request('DELETE', '/api/books/' . $bookId);

        return $this->getClientResponse();
    }

    protected function getNotes(int $bookId)
    {
        $this->client->request('GET', '/api/books/' . $bookId . '/notes');

        return $this->getData();
    }

    protected function deleteNote(int $noteId)
    {
        $this->client->request('DELETE', '/api/notes/' . $noteId);

        return $this->getClientResponse();
    }
}