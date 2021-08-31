<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditTrickController extends AbstractController
{
    /**
     * @Route("/edit/trick", name="edit_trick")
     */
    public function index(): Response
    {
        return $this->render('edit_trick/index.html.twig', [
            'controller_name' => 'EditTrickController',
        ]);
    }
}
