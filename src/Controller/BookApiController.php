<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class BookApiController extends ApiController
{
    /**
     * @Route("/api/books", name="apiBooks")
     */
    public function getBooks(): JsonResponse
    {
        $models = $this->findAllBooksByUser($this->getUser());

        return $this->createApiResponse(['data' => $models]);
    }

    /**
     * @Route("/api/books/{book}/notes", name="apiNotes")
     */
    public function getNotesForBook(Book $book): JsonResponse
    {
        $models = [];
        foreach ($book->getNotes() as $note) {
            $models[] = $this->createNoteApiModel($note);
        }

        return $this->createApiResponse(['data' => $models]);
    }
}