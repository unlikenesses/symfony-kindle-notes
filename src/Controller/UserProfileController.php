<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 */
class UserProfileController extends AbstractController
{
    /**
     * @Route("/user/profile", name="app_profile")
     */
    public function index()
    {
        return $this->render('user_profile/index.html.twig', []);
    }
}
