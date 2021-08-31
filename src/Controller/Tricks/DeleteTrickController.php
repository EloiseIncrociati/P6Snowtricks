<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteTrickController extends AbstractController
{
    /**
     * @Route("/delete/trick", name="delete_trick")
     */
    public function index(): Response
    {
        return $this->render('delete_trick/index.html.twig', [
            'controller_name' => 'DeleteTrickController',
        ]);
    }
}
