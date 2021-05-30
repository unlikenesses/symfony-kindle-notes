<?php

namespace App\Controller;

use App\Message\ImportFile;
use App\Service\FileUploader;
use App\ValueObject\FileToImport;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
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
    public function import(Request $request, FileUploader $fileUploader, MessageBusInterface $messageBus): Response
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

        $filename = $fileUploader->upload($request->files->get('clippings_file'));
        $fileToImport = new FileToImport($filename, $this->getUser());

        $message = new ImportFile($fileToImport);
        $messageBus->dispatch($message);

        return $this->redirectToRoute('books');
    }
}
