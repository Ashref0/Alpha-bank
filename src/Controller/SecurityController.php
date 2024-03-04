<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mailer\MailerInterface;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Email;



class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, UserRepository $userRepository, MailerInterface $mailer): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
 
        $user = $userRepository->findOneByUsername($lastUsername);

        if ($user !== null) {
        $email = (new Email())
            ->from('dridi.mohamedsadok@gmail.com') // Consider using a parameter or configuration value for the sender email
            ->to($user->getEmail())
            ->subject('Confirmation de login')
            ->html('<p>Votre Compte a été effectuée avec succès.</p>');

        $mailer->send($email);
        }
    

        return $this->render('security1/login-light-login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
        
    }
}
