<?php

namespace App\Controller;

use App\Service\FileHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

class ImportController extends AbstractController
{
    /**
     * @Route("/import", name="show_import", methods={"GET"})
     */
    public function index(): Response
    {

        return $this->render('import/index.html.twig', []);
    }

    /**
     * @Route("/import", name="import", methods={"POST"})
     */
    public function import(Request $request, FileHandler $fileHandler): Response
    {
        if (!$this->isCsrfTokenValid('import', $request->request->get('token'))) {
            throw new InvalidCsrfTokenException();
        }
        $books = $fileHandler->handleFile($request->files->get('clippings_file'));

        return $this->json($books);
    }
}
