<?php

namespace App\Controller;

use App\Service\NoteSaver;
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
        return $this->render('import/index.html.twig', ['errors' => []]);
    }

    /**
     * @Route("/import", name="import", methods={"POST"})
     */
    public function import(Request $request, FileHandler $fileHandler, NoteSaver $noteSaver): Response
    {
        if (!$this->isCsrfTokenValid('import', $request->request->get('token'))) {
            throw new InvalidCsrfTokenException();
        }
        if (! $request->files->get('clippings_file')) {
            return $this->render(
                'import/index.html.twig',
                ['errors' => ['Please specify a file to upload.']]
            );
        }
        $books = $fileHandler->handleFile($request->files->get('clippings_file'));
        $noteSaver->storeNotes($books);

        return $this->redirectToRoute('books');
    }
}
