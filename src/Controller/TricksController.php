<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{
    /**
     * @Route("/tricks", name="tricks")
     */
    public function addTricks(): Response
    {
        return $this->render('tricks/add.html.twig', [
            'controller_name' => 'TricksController',
        ]);
    }
}
