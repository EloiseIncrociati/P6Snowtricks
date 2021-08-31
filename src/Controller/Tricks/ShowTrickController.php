<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowTrickController extends AbstractController
{
    /**
     * @Route("/show/trick", name="show_trick")
     */
    public function index(): Response
    {
        return $this->render('show_trick/index.html.twig', [
            'controller_name' => 'ShowTrickController',
        ]);
    }
}
