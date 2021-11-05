<?php

namespace App\Controller;

use App\Entity\PasswordForgot;
use App\Entity\PasswordReset;
use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordForgotType;
use App\Form\PasswordResetType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    public function logout(): void
    {

    }

    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPassword());
            $createdAt = new \DateTime();
            $confirmation_token = md5(random_bytes(60));
            $user->setPassword($password)
                ->setCreatedAt($createdAt)
                ->setActivate(false)
                ->setRoles('ROLE_USER')
                ->setToken($confirmation_token);
            $manager->persist($user);
            $manager->flush();
            $subject = 'Confirmation de compte';
            $content = $this->renderView('emails/registration.html.twig', [
                'username' => $user->getUsername(),
                'id' => $user->getId(),
                'token' => $user->getToken(),
                'address' => $request->server->get('SERVER_NAME')
            ]);
            $headers = 'From: "Snowtricks"<eloise.incrociati@gmail.com>' . "\n";
            $headers .= 'Reply-To: foliomoon@gmail.com' . "\n";
            $headers .= 'Content-Type: text/html; charset="iso-8859-1"' . "\n";
            $headers .= 'Content-Transfer-Encoding: 8bit';
            mail($user->getEmail(), $subject, $content, $headers);
            $this->addFlash('success', 'Votre compte a bien été créé.');
            return $this->redirectToRoute('account_register');
        }
        return $this->render('account/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function confirm(Request $request, UserRepository $repo, EntityManagerInterface $manager): ?Response
    {
        if (!$request->query->get('id')) {
            throw new Exception('Veuillez cliquer sur le lien dans l\'email qui vous a été envoyé !');
        }
        if (!$request->query->get('token')) {
            throw new Exception('Veuillez cliquer sur le lien dans l\'email qui vous a été envoyé !');
        }

        $id = $request->query->get('id');
        $token = $request->query->get('token');

        /** @var User $user */
        $user = $repo->findOneBy(['id' => $id]);
        if ($user->getId() && $user->getToken() === $token) {
            $user->setToken(null)
                ->setActivate(true);
            $manager->flush();
            $this->addFlash('success', 'Votre compte est validé ! Connectez-vous !');
            return $this->redirectToRoute('account_login');
        }

        throw new Exception('Veuillez cliquer sur le lien dans l\'email !');
    }

    public function profile(Request $request, EntityManagerInterface $manager)
    {
        /** @var User $user */
        $user = $this->getUser();
        $userDb = $manager->createQuery('SELECT u FROM App\Entity\User u WHERE u.id = :id')->setParameter('id', $user->getId())->getScalarResult();
        $form = $this->createForm(AccountType::class, $user, array('user' => $this->getUser()));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->flush();
            $this->addFlash('success', 'Votre compte a été mis à jour.');
            return $this->redirectToRoute('account_profile');
        }
        return $this->render('account/profile.html.twig', [
            'form' => $form->createView(),
            'user' => $userDb[0],
        ]);
    }

    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager): Response
    {
        $passwordUpdate = new PasswordUpdate();
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $passwordUpdate->getNewPassword();
            $password = $encoder->encodePassword($user, $newPassword);
            $user->setPassword($password);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Votre mot de passe a été mis à jour.');
            return $this->redirectToRoute('account_password');
        }
        return $this->render('account/update-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function forgotPassword(Request $request, UserRepository $repo, EntityManagerInterface $manager): Response
    {
        $passwordForgot = new PasswordForgot();
        $form = $this->createForm(PasswordForgotType::class, $passwordForgot);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($repo->findOneBy(['username' => $passwordForgot->getUsername()])) {
                /** @var User $user */
                $user = $repo->findOneBy(['username' => $passwordForgot->getUsername()]);
                $confirmation_token = md5(random_bytes(60));
                $user->setToken($confirmation_token);
                $manager->flush();
                $subject = 'Réinitialisation du mot de passe';
                $content = $this->renderView('emails/forgot-password.html.twig', [
                        'username' => $user->getUsername(),
                        'id' => $user->getId(),
                        'token' => $user->getToken(),
                        'address' => $request->server->get('SERVER_NAME'),
                    ]
                );
                $headers = 'From: "Snowtricks"<eloise.incrociati@gmail.com>' . "\n";
                $headers .= 'Reply-To: foliomoon@gmail.com' . "\n";
                $headers .= 'Content-Type: text/html; charset="iso-8859-1"' . "\n";
                $headers .= 'Content-Transfer-Encoding: 8bit';
                mail($user->getEmail(), $subject, $content, $headers);
                $this->addFlash('success', 'Un email vient de vous être envoyé pour réinitialiser votre mot de passe !');
                return $this->redirectToRoute('account_forgot');
            }

            $this->addFlash('danger', 'Cet utilisateur n\'existe pas.');
            return $this->redirectToRoute('account_forgot');
        }
        return $this->render('account/forgot-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function resetPassword(Request $request, UserRepository $repo, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        if (!$request->query->get('id')) {
            throw new Exception('Veuillez cliquer sur le lien qui vous a été envoyé pour réinitialiser votre mot de passe !');
        }
        if (!$request->query->get('token')) {
            throw new Exception('Veuillez cliquer sur le lien qui vous a été envoyé pour réinitialiser votre mot de passe !');
        }

        $token = $request->query->get('token');
        $id = $request->query->get('id');

        $passwordReset = new PasswordReset();
        /** @var User $user */
        $user = $repo->findOneBy(['id' => $id]);

        $form = $this->createForm(PasswordResetType::class, $passwordReset);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($user->getId()) {
                if ($user->getEmail() === $passwordReset->getEmail()) {
                    if ($user->getToken() === $token) {
                        $newPassword = $passwordReset->getNewPassword();
                        $password = $encoder->encodePassword($user, $newPassword);
                        $user->setToken(null)
                            ->setPassword($password);
                        $manager->persist($user);
                        $manager->flush();
                        $this->addFlash('success', 'Votre mot de passe a été mis à jour ! Connectez-vous !');
                        return $this->redirectToRoute('account_login');
                    }

                    throw new Exception('Veuillez cliquer sur le lien qui vous a été envoyé pour réinitialiser votre mot de passe !');
                }

                $this->addFlash('success', 'Wrong email adress !');
                return $this->redirectToRoute('account_login');
            }

            throw new Exception('Veuillez cliquer sur le lien qui vous a été envoyé pour réinitialiser votre mot de passe !');
        }

        return $this->render('account/reset-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function generateUniqueFileName(): string
    {
        return md5(uniqid('', true));
    }
}