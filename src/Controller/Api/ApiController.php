<?php

namespace App\Controller\Api;

use App\Api\BookApiModel;
use App\Api\NoteApiModel;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Note;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ApiController extends AbstractController
{
    protected const API_ERROR_CODES = [
        'too many tags' => 10,
        'too many categories' => 20,
    ];

    protected function createApiResponse(array $data, int $statusCode = 200): JsonResponse
    {
        $json = $this->get('serializer')->serialize($data, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => [
                'book', 'notes',
            ]
        ]);

        return new JsonResponse($json, $statusCode, [], true);
    }

    protected function createBookApiModel(Book $book): BookApiModel
    {
        $model = new BookApiModel();
        $model->id = $book->getId();
        $model->title = $book->getTitle();
        $model->author = $book->getAuthorFirstName() . ' ' . $book->getAuthorLastName();
        $model->firstName = $book->getAuthorFirstName();
        $model->lastName = $book->getAuthorLastName();
        $model->categories = $this->createBookCategories($book);

        return $model;
    }

    protected function createBookCategories(Book $book): array
    {
        return array_map(function (Category $category) {
            return [
                'id' => $category->getId(),
                'name' => $category->getName(),
            ];
        }, $book->getCategory()->toArray());
    }

    protected function createNoteApiModel(Note $note, bool $withBook = false): NoteApiModel
    {
        $model = new NoteApiModel();
        $model->id = $note->getId();
        $model->date = $note->getDate()->format('d/m/Y h:i:s');
        $model->page = $note->getPage();
        $model->location = $note->getLocation();
        $model->note = $note->getNote();
        $model->type = $note->getType();
        $model->tags = $note->getTags();
        if ($withBook) {
            $model->source = '';
            if ($book = $note->getBook()) {
                $bookModel = $this->createBookApiModel($book);
                $model->source = $bookModel->author . ', ' . $bookModel->title;
            }
        }

        return $model;
    }
}
