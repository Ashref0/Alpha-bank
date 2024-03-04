<?php

namespace App\Controller;
use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use \DateTime;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\Email;

class TransactionController extends AbstractController
{
    #[Route('/main', name: 'main')]
    public function main(): Response
    {
        $data = $this->getDoctrine()->getRepository(Transaction::class)->findAll();

    return $this->render('Transaction/main.html.twig', [
            'list' => $data
        ]);
    
    }
    #[Route('/fronttransa', name: 'fronttransa')]
    public function main2(): Response
    {
        $data = $this->getDoctrine()->getRepository(Transaction::class)->findAll();

    return $this->render('Transaction/front.html.twig', [
            'list' => $data
        ]);
    
    }
    

  /**
 * @Route("/transaction", name="transaction")
 */
public function Transaction(Request $request,TransactionRepository $tr, ClientRepository $cr, MailerInterface $mailer): Response
{
    $transaction = new Transaction();
    $utilisateurId = 10;
        
    $client2 = $cr->findOneBy(['id' => $utilisateurId]);
    $form = $this->createForm(TransactionType::class, $transaction);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $client = $transaction->getClient();
        $transaction->setDateTransaction(new DateTime('now'));
        $transaction->setClient($client);
        $transaction->setClient2($client2);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($transaction);
        $entityManager->flush();
        $client2->setSolde($client2->getSolde() - $transaction->getMontant());
        $client->setSolde($client->getSolde() + $transaction->getMontant());
        $entityManager->persist($client);
        $entityManager->persist($client2);
        $entityManager->flush();

        $email = (new Email())
            ->from('dridi.mohamedsadok@gmail.com')
            ->to($client->getEmail())
            ->subject('Confirmation de transaction')
            ->html('<p>Votre transaction a été effectuée avec succès.</p> ');

        $mailer->send($email);

        return $this->redirectToRoute('fronttransa');
    }

    return $this->render('transaction/index.html.twig', [
        'form' => $form->createView(),
    ]);
}
/**
 * @Route("/mypdf/{id}", name="pdf")
 */
public function imprimerpdf($id, TransactionRepository $transactionRepository)
{
    $transaction = $transactionRepository->find($id);
    
    if (!$transaction) {
        throw $this->createNotFoundException('The transaction does not exist');
    }

    $options = new Options();
    $options->set('defaultFont', 'Arial');
    
    $dompdf = new Dompdf($options);

    $html = $this->renderView('transaction/mypdf.html.twig', [
        'data' => $transaction
    ]);

    $dompdf->loadHtml($html);

    $dompdf->setPaper('A4', 'portrait');

    $dompdf->render();

    $response = new Response($dompdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="transaction.pdf"'
    ]);

    return $response;
}

    /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmation(): Response
    {
        return $this->render('transaction/confirmation.html.twig');
    }
    
    /**
     * @Route("/update/{id}", name="update")
     */
     public function update(Request $request, $id)
{
    $transaction = $this->getDoctrine()->getRepository(Transaction::class)->find($id);
    if (!$transaction) {
        throw $this->createNotFoundException('Transaction not found');
    }

    $form = $this->createForm(TransactionType::class, $transaction);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        $this->addFlash('notice', 'Transaction updated successfully!!');

        return $this->redirectToRoute('confirmation');
    }

    return $this->render('transaction/update.html.twig', [
        'form' => $form->createView(),
        'transaction' => $transaction,
    ]);
}

     

    /**
     * @Route("/delete/{id}", name="delete")
     */

     public function delete($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $transaction = $entityManager->getRepository(Transaction::class)->find($id);
        if (!$transaction) {
            throw $this->createNotFoundException('Transaction not found');
        }
    
        $entityManager->remove($transaction);
        $entityManager->flush();
    
        $this->addFlash('notice', 'Delete successfully!!');
    
        return $this->redirectToRoute('confirmation');
    }
    
}
