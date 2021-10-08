<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrickRepository;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     *
     * @param TrickRepository $trickRepository
     * @return Response
     */
    public function index(TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->findAll();

        return $this->render('home/homepage.html.twig', [
            'tricks' => $tricks
        ]);
    }

    /**
     * Display the single trick page with trick data and the add comment form
     *
     * @Route("/trick/{id}", name="trick_show")
     *
     * @param Trick $trick
     * @param MediaRepository $mediaRepository
     * @param CommentRepository $commentRepository
     * @param Request $request
     * @param EntityManagerInterface $manager
     *
     * @return Response
     * @throws \Exception
     */
    public function show(Trick $trick, MediaRepository $mediaRepository, CommentRepository $commentRepository, Request $request, EntityManagerInterface $manager): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime())
                ->setTrick($trick)
                ->setUser($this->getUser());
            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute('trick_show', ['id' => $trick->getId(), '_fragment' => $comment->getId()]);
        }
        $medias = $mediaRepository->findBy(['trick' => $trick->getId()]);
        $comments = $commentRepository->findBy(['trick' => $trick->getId()]);
        return $this->render('home/show.html.twig', [
            'trick' => $trick,
            'medias' => $medias,
            'comments' => $comments,
            'form' => $form->createView(),
        ]);
    }
}
