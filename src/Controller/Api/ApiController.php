<?php

namespace App\Controller\Api;

use App\Api\BookApiModel;
use App\Api\NoteApiModel;
use App\Entity\Book;
use App\Entity\Note;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ApiController extends AbstractController
{
    protected function createApiResponse(array $data, int $statusCode = 200): JsonResponse
    {
        $json = $this->get('serializer')->serialize($data, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => [
                'book', 'notes',
            ]
        ]);

        return new JsonResponse($json, $statusCode, [], true);
    }

    protected function findAllBooksByUser(User $user): array
    {
        $books = $this->getDoctrine()
            ->getRepository('App:Book')
            ->findByUser($user);
        $models = [];
        foreach ($books as $book) {
            $models[] = $this->createBookApiModel($book);
        }

        return $models;
    }

    protected function createBookApiModel(Book $book): BookApiModel
    {
        $model = new BookApiModel();
        $model->id = $book->getId();
        $model->title = $book->getTitle();
        $model->author = $book->getAuthorFirstName() . ' ' . $book->getAuthorLastName();

        return $model;
    }

    protected function createNoteApiModel(Note $note): NoteApiModel
    {
        $model = new NoteApiModel();
        $model->id = $note->getId();
        $model->date = $note->getDate()->format('d/m/Y h:i:s');
        $model->page = $note->getPage();
        $model->location = $note->getLocation();
        $model->note = $note->getNote();
        $model->type = $note->getType();
        $model->tags = $note->getTags();

        return $model;
    }
}
