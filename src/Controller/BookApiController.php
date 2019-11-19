<?php

namespace App\Controller;

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
}