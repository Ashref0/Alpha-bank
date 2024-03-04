<?php

namespace App\Controller;
use App\Entity\Relever;
use App\Form\ReleverType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReleverController extends AbstractController
{
    #[Route('/showrelever', name: 'showrelever')]
    public function main(): Response
    {
        $data = $this->getDoctrine()->getRepository(Relever::class)->findAll();

    return $this->render('Relever/main.html.twig', [
            'list' => $data
        ]);
    
    }


     /**
    * @Route("/relever", name="relever")
    */
    public function relever(Request $request): Response
    {
        $relever = new Relever();
        $form = $this->createForm(ReleverType::class, $relever);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($relever);
            $entityManager->flush();

        return $this->redirectToRoute('confirmation');
        }

        return $this->render('relever/index.html.twig', [
        'form' => $form->createView(),
         ]);
    }

     /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmation(): Response
    {
        return $this->render('transaction/confirmation.html.twig');
    }
}
