<?php

namespace App\Controller;

use App\Entity\Credit;
use App\Form\CreditType;
use App\Repository\CreditRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/credit')]
class CreditController extends AbstractController
{
    #[Route('/', name: 'app_credit_index', methods: ['GET'])]
    public function index(CreditRepository $creditRepository): Response
    {
        $credits = $creditRepository->findAll();
        $statusOrder = ['En attente', 'Accepté', 'Fermé', 'Refusé'];
        usort($credits, function ($a, $b) use ($statusOrder) {
            return array_search($a->getStatus(), $statusOrder) - array_search($b->getStatus(), $statusOrder);
        });
        return $this->render('credit/index.html.twig', [
            'credits' => $credits,
        ]);
    }

    #[Route('/back', name: 'app_credit_indexback', methods: ['GET'])]
    public function indexback(CreditRepository $creditRepository): Response
    {
        $credits = $creditRepository->findAll();
        $statusOrder = ['En attente', 'Accepté', 'Fermé', 'Refusé'];
        usort($credits, function ($a, $b) use ($statusOrder) {
            return array_search($a->getStatus(), $statusOrder) - array_search($b->getStatus(), $statusOrder);
        });
        return $this->render('credit/indexback.html.twig', [
            'credits' => $credits,
        ]);
    }

    #[Route('/new', name: 'app_credit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $credit = new Credit();
        $form = $this->createForm(CreditType::class, $credit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $credit->setDateDemande(new \DateTime('now'));
            $credit->setMontantPaye(0);
            $credit->setDateEcheance(new \DateTime('now'));
            $credit->setStatus('En attente');

            $creditType = $credit->getType();
            $montantEmprunte = $credit->getMontantEmprunte();

            switch ($creditType) {
                case 'Personal':
                    if ($montantEmprunte >= 1000 && $montantEmprunte <= 10000) {
                        $credit->setTauxInteret(6);
                    } elseif ($montantEmprunte > 10000 && $montantEmprunte <= 25000) {
                        $credit->setTauxInteret(6.5);
                    }
                    elseif ($montantEmprunte > 25000)
                    {
                    $credit->setTauxInteret(7);
                    }
                    break;

                case 'Home':
                    if ($montantEmprunte >= 1000 && $montantEmprunte <= 200000) {
                        $credit->setTauxInteret(7);
                    } elseif ($montantEmprunte > 200000 && $montantEmprunte <= 400000) {
                        $credit->setTauxInteret(7.5);
                    }
                    elseif ($montantEmprunte > 400000)
                    {
                        $credit->setTauxInteret(8);
                    }
                    break;

                case 'Business':
                    if ($montantEmprunte >= 1000 && $montantEmprunte <= 50000) {
                        $credit->setTauxInteret(8);
                    } elseif ($montantEmprunte > 50000 && $montantEmprunte <= 100000) {
                        $credit->setTauxInteret(8.5);
                    }
                    elseif ($montantEmprunte > 100000)
                    {
                        $credit->setTauxInteret(9);
                    }
                    break;

                case 'Car':
                    if ($montantEmprunte >= 1000 && $montantEmprunte <= 35000) {
                        $credit->setTauxInteret(8);
                    } elseif ($montantEmprunte > 35000 && $montantEmprunte <= 70000) {
                        $credit->setTauxInteret(8.5);
                    }
                    elseif ($montantEmprunte > 70000)
                    {
                        $credit->setTauxInteret(9);
                    }
                    break;
            }

            $interestRate = $credit->getTauxInteret() / 100;
            $timeInMonths = $credit->getNbMois();
            $timeInYears = $timeInMonths / 12;


            $amount = $montantEmprunte * (1 + ($interestRate * $timeInYears));
            $credit->setMontant($amount);
            $mensualité = $amount /  $timeInMonths ;
            $credit->setMensualite($mensualité);

            $entityManager->persist($credit);
            $entityManager->flush();

            $email = (new Email())
                ->from('mansouriferyel57@gmail.com')
                ->to('mansouriferyel57@gmail.com')
                ->subject('Credit!')
                ->text('You have applied for a new credit!');

            $mailer->send($email);

            return $this->redirectToRoute('app_credit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('credit/new.html.twig', [
            'credit' => $credit,
            'form' => $form,
        ]);
    }

    #[Route('/accept/{idcredit}', name: 'app_accept_credit', methods: ['GET', 'POST'])]
    public function accept(Request $request, EntityManagerInterface $entityManager, $idcredit): Response
    {
        $credit = $entityManager->getRepository(Credit::class)->find($idcredit);
        $credit->setStatus('Accepté');

        $entityManager->persist($credit);
        $entityManager->flush();

        return $this->redirectToRoute('app_credit_indexback', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/refuse/{idcredit}', name: 'app_refuse_credit', methods: ['GET', 'POST'])]
    public function refuse(Request $request, EntityManagerInterface $entityManager, $idcredit): Response
    {
        $credit = $entityManager->getRepository(Credit::class)->find($idcredit);
        $credit->setStatus('Refusé');

        $entityManager->persist($credit);
        $entityManager->flush();

        return $this->redirectToRoute('app_credit_indexback', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_credit_show', methods: ['GET'])]
    public function show(Credit $credit): Response
    {
        return $this->render('credit/show.html.twig', [
            'credit' => $credit,
        ]);
    }

    #[Route('/back/{id}', name: 'app_credit_showback', methods: ['GET'])]
    public function showback(Credit $credit): Response
    {
        return $this->render('credit/showback.html.twig', [
            'credit' => $credit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_credit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Credit $credit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CreditType::class, $credit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_credit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('credit/edit.html.twig', [
            'credit' => $credit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_credit_delete', methods: ['POST'])]
    public function delete(Request $request, Credit $credit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$credit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($credit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_credit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/pdf/{id}', name: 'app_credit_pdf')]
    public function generatePDF(EntityManagerInterface $entityManager, $id): Response
    {
        $credit = $entityManager->getRepository(Credit::class)->find($id);

        $html = $this->renderView('credit/details.html.twig', [
            'credit' => $credit,
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $response = new Response();
        $response->setContent($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="credit.pdf"');

        return $response;
    }

}
