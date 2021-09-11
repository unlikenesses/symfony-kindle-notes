<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookController extends AbstractController
{
    /**
     * @Route("/books", name="books")
     * @Route("/books/category/{category}", name="booksCategory")
     */
    public function index(): Response
    {
        return $this->render('book/index.html.twig');
    }

    /**
     * @Route("/truncate-db", name="truncate_db")
     */
    public function truncateDb(): Response
    {
        $conn = $this->getDoctrine()->getConnection();
        $sql = 'SET FOREIGN_KEY_CHECKS = 0;';
        $sql .= 'TRUNCATE TABLE note; TRUNCATE TABLE book;';
        $sql .= 'TRUNCATE TABLE category; TRUNCATE TABLE book_category;';
        $sql .= 'TRUNCATE TABLE tag; TRUNCATE TABLE note_tag;';
        $sql .= 'SET FOREIGN_KEY_CHECKS = 1;';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $this->redirectToRoute('show_import');
    }
}
