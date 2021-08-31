<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateTrickController extends AbstractController
{
    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var FormFactoryInterface
     */
    private $form;

    /**
     * @var CreateEditTrickForm
     */
    private $createEditTrickForm;

    /**
     * @var CreateEditTrickForm
     */
    private $createEditTrickForm;

    public function __construct(
        UrlGeneratorInterface $router,
        Environment $twig,
        FormFactoryInterface $form,
        CreateEditTrickForm $createEditTrickForm
        )
    {
        $this->router = $router;
        $this->twig = $twig;
        $this->form = $form;
        $this->createEditTrickForm = $createEditTrickForm;
    }

    /**
     * @Route("/create/trick", name="create_trick")
     */
    public function index(): Response
    {
        $formTrick = $this->form->create(TrickType::class, $trick = new Trick());
        $formTrick->handleRequest($request);

        if ($this->createEditTrickForm->handleForm($formTrick, $trick)) {
            $request->getSession()->getFlashBag()->add(
                'Notice',
                'Your Trick has been created !'
            );
            return new RedirectResponse($this->router->generate(
                'show_trick',
                ['slug' => $trick->getSlug()]
            ));
        } else {
            return new Response($this->twig->render(
                'tricks/createTrick.html.twig', [
                'trick' => $trick,
                'formTrick' => $formTrick->createView()
            ]));
        }
    }
}
