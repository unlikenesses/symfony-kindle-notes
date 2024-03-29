<?php

namespace App\Tests\Functional\Traits;

trait HasAPIInteraction
{
    protected function getBooks(string $category = 'null')
    {
        $this->client->request('GET', '/api/books/category/' . $category);

        return $this->getData();
    }

    protected function deleteBook(int $bookId)
    {
        $this->client->request('DELETE', '/api/books/' . $bookId);

        return $this->getClientResponse();
    }

    protected function permaDeleteBook(array $bookIds)
    {
        $this->client->request('PUT', '/api/books/permaDelete', [], [], [], json_encode($bookIds));

        return $this->getClientResponse();
    }

    protected function restoreBook(array $bookIds)
    {
        $this->client->request('PUT', '/api/books/restore', [], [], [], json_encode($bookIds));

        return $this->getClientResponse();
    }

    protected function updateBookTitle(int $bookId, string $title)
    {
        $this->client->request('PUT', '/api/books/' . $bookId . '/title', [], [], [], json_encode($title));

        return $this->getClientResponse();
    }

    protected function updateBookAuthor(int $bookId, string $firstName, string $lastName)
    {
        $this->client->request('PUT', '/api/books/' . $bookId . '/author', [], [], [], json_encode([
            'firstName' => $firstName,
            'lastName' => $lastName,
        ]));

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

    protected function permaDeleteNote(array $noteIds)
    {
        $this->client->request('PUT', '/api/notes/permaDelete', [], [], [], json_encode($noteIds));

        return $this->getClientResponse();
    }

    protected function restoreNote(array $noteIds)
    {
        $this->client->request('PUT', '/api/notes/restore', [], [], [], json_encode($noteIds));

        return $this->getClientResponse();
    }
}