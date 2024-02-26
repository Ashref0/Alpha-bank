<?php

namespace App\Controller;

use App\Entity\Credit;
use App\Entity\Remboursement;
use App\Form\RemboursementType;
use App\Repository\RemboursementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/remboursement')]
class RemboursementController extends AbstractController
{
    #[Route('/', name: 'app_remboursement_index', methods: ['GET'])]
    public function index(RemboursementRepository $remboursementRepository): Response
    {
        return $this->render('remboursement/index.html.twig', [
            'remboursements' => $remboursementRepository->findAll(),
        ]);
    }

    #[Route('/credit/{idcredit}', name: 'app_remboursement_list', methods: ['GET'])]
    public function list(RemboursementRepository $remboursementRepository, EntityManagerInterface $entityManager, $idcredit): Response
    {
        $credit = $entityManager->getRepository(Credit::class)->find($idcredit);
        $_remboursements = $credit->getRemboursements();

        return $this->render('remboursement/index.html.twig', [
            'remboursements' => $_remboursements,
        ]);
    }

    #[Route('/creditback/{idcredit}', name: 'app_remboursement_listback', methods: ['GET'])]
    public function listback(RemboursementRepository $remboursementRepository, EntityManagerInterface $entityManager, $idcredit): Response
    {
        $credit = $entityManager->getRepository(Credit::class)->find($idcredit);
        $_remboursements = $credit->getRemboursements();

        return $this->render('remboursement/indexback.html.twig', [
            'remboursements' => $_remboursements,
        ]);
    }


    #[Route('/new/{idcredit}', name: 'app_remboursement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $idcredit, MailerInterface $mailer): Response
    {
        $credit = $entityManager->getRepository(Credit::class)->find($idcredit);
        $remboursement = new Remboursement();
        $form = $this->createForm(RemboursementType::class, $remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $remboursement->setDateRemboursement(new \DateTime('now'));
            $remboursement->setCredit($credit);

            $credit->setMontantPaye($credit->getMontantPaye() + $remboursement->getMontant());

            if ($credit->getMontantPaye() == $credit->getMontant())
                $credit->setStatus('Fermé');

            $entityManager->persist($remboursement);
            $entityManager->persist($credit);
            $entityManager->flush();


            $email = (new Email())
                ->from('mansouriferyel57@gmail.com')
                ->to('mansouriferyel57@gmail.com')
                ->subject('Remboursement!')
                ->text('You have applied for a new remboursement!');

            $mailer->send($email);

            return $this->redirectToRoute('app_remboursement_list', ['idcredit' => $idcredit], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('remboursement/new.html.twig', [
            'remboursement' => $remboursement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_remboursement_show', methods: ['GET'])]
    public function show(Remboursement $remboursement): Response
    {
        return $this->render('remboursement/show.html.twig', [
            'remboursement' => $remboursement,
        ]);
    }

    #[Route('/back/{id}', name: 'app_remboursement_showback', methods: ['GET'])]
    public function showback(Remboursement $remboursement): Response
    {
        return $this->render('remboursement/showback.html.twig', [
            'remboursement' => $remboursement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_remboursement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Remboursement $remboursement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RemboursementType::class, $remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_remboursement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('remboursement/edit.html.twig', [
            'remboursement' => $remboursement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_remboursement_delete', methods: ['POST'])]
    public function delete(Request $request, Remboursement $remboursement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$remboursement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($remboursement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_remboursement_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/pdf/{id}', name: 'app_remboursement_pdf')]
    public function generatePDF(EntityManagerInterface $entityManager, $id): Response
    {
        $rem = $entityManager->getRepository(Remboursement::class)->find($id);

        $html = $this->renderView('remboursement/details.html.twig', [
            'remboursement' => $rem,
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $response = new Response();
        $response->setContent($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="remboursement.pdf"');

        return $response;
    }

}
