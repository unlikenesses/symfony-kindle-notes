<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/categories", name="categories")
     */
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $categories = $user->getCategories();

        return $this->render('category/index.html.twig', [
            'categories' => $this->assignBooksToCategories($categories),
        ]);
    }

    private function assignBooksToCategories(Collection $categories): array
    {
        $assigned = [];
        /** @var Category $category */
        foreach ($categories as $category) {
            $assigned[$category->getName()] = [
                'id' => $category->getId(),
                'books' => $category->getBooks(),
            ];
        }

        return $assigned;
    }
}