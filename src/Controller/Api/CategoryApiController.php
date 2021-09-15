<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CategoryApiController extends ApiController
{
    private $entityManager;

    private const MAX_CATEGORIES = 50;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/categories", name="apiCategories")
     */
    public function getCategories()
    {
        /** @var User $user */
        $user = $this->getUser();
        $categories = array_map(function (Category $row) {
            return $row->getName();
        }, $user->getCategories()->toArray());

        return $this->createApiResponse(['categories' => $categories]);
    }

    /**
     * @Route("/api/books/{book}/categories", name="apiBookCategories", methods="PUT")
     */
    public function updateBookCategories(Book $book, Request $request): JsonResponse
    {
        $categories = json_decode($request->getContent());
        if ($this->addingCategory($book, $categories) && $this->overCategoryLimit($categories)) {
            $message = 'too many categories';
            return $this->createApiResponse([
                'message' => $message,
                'error' => self::API_ERROR_CODES[$message],
            ], 500);
        }
        $categoryIds = $this->syncBookCategories($book, $categories);

        return $this->createApiResponse([
            'message' => 'success',
            'categories' => $this->createBookCategories($book),
        ]);
    }

    private function addingCategory(Book $book, array $categories): bool
    {
        return count($categories) > count($book->getCategory());
    }

    private function overCategoryLimit(array $categories): bool
    {
        /** @var User $user */
        $user = $this->getUser();
        $existingUserCategories = $user->getCategories()->toArray();
        $existingUserCategories = array_map(function (Category $row) {
            return $row->getName();
        }, $existingUserCategories);

        return count($existingUserCategories) >= self::MAX_CATEGORIES && array_diff($categories, $existingUserCategories);
    }

    private function syncBookCategories(Book $book, array $categoryNames): array
    {
        $categories = $this->getCategoryEntities($categoryNames);
        $book->syncCategories($categories);
        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $categories;
    }

    private function getCategoryEntities(array $categoryNames): array
    {
        $categories = [];
        foreach ($categoryNames as $categoryName) {
            $categories[] = $this->getCategoryEntity($categoryName);
        }

        return $categories;
    }

    private function getCategoryEntity(string $categoryName): Category
    {
        $categoryRepo = $this->entityManager->getRepository(Category::class);
        /** @var Category $category */
        $category = $categoryRepo->findOneBy(['name' => $categoryName]);
        if ($category) {
            return $category;
        }
        $category = new Category();
        $category->setName($categoryName);
        $category->setUser($this->getUser());
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }
}