<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class RedirectController extends AbstractController
{
    #[Route('/redirect', name: 'app_redirect')]
    public function index(): Response
    {
        return $this->render('redirect/index.html.twig', [
            'controller_name' => 'RedirectController',
        ]);
    }

    /**
     * @Route("/home", name="home")
     */
    public function home()
    {
        return $this->render('frontoffice/homefront.html.twig');
    }

    /**
     * @Route("/homeback", name="homeback")
     */
    public function homeback()
    {
        return $this->render('backoffice/homeback.html.twig');
    }

    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('mansouriferyel57@gmail.com')
            ->to('mansouriferyel57@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        try {
            $mailer->send($email);
            return new Response(
                'Email sent!'
            );
        } catch (TransportExceptionInterface $e) {
            return new Response(
                'Email not sent!'
            );
        }
    }

    #[Route('/pdf')]
    public function generatePDF(): Response
    {
        try {
            $dompdf = new Dompdf();
            $dompdf->loadHtml('hello world');
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            $response = new Response();
            $response->setContent($dompdf->output());
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'attachment; filename="exemple.pdf"');

            return $response;
        } catch (\Exception $e) {
            return new Response('Error generating PDF: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
