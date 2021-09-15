<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrashController extends AbstractController
{
    /**
     * @Route("/trash", name="trash")
     */
    public function index(): Response
    {
        return $this->render('trash/index.html.twig');
    }
}