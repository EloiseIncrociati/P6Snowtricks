<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var TrickRepository
     */
    private $trickRepository;

    private $numberTricksDisplayBegin = 8;


    public function __construct(
        Environment $twig,
        TrickRepository $trickRepository
    )
    {
        $this->twig = $twig;
        $this->trickRepository = $trickRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function homepage()
    {
        $tricks = $this->trickRepository->findBy([],['createdAt' => 'DESC'],$this->numberTricksDisplayBegin);

        return new Response($this->twig->render(
            'tricks/tricks.html.twig', [
            'tricks' => $tricks
        ]));
    }
    /*
     * @Route("/list", name="list")
    public function index(): Response
    {
        return $this->render('list/index.html.twig', [
            'controller_name' => 'ListController',
        ]);
    }*/
}
