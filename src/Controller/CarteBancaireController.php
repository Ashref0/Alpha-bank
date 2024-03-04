<?php

namespace App\Controller;

use App\Entity\CarteBancaire;
use App\Form\CarteBancaireType;
use App\Repository\CarteBancaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/carte/bancaire')]
class CarteBancaireController extends AbstractController
{
    #[Route('/', name: 'app_carte_bancaire_index', methods: ['GET'])]
    public function index(CarteBancaireRepository $carteBancaireRepository): Response
    {
        return $this->render('carte_bancaire/index.html.twig', [
            'carte_bancaires' => $carteBancaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_carte_bancaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $carteBancaire = new CarteBancaire();
        $form = $this->createForm(CarteBancaireType::class, $carteBancaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($carteBancaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_carte_bancaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carte_bancaire/new.html.twig', [
            'carte_bancaire' => $carteBancaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_carte_bancaire_show', methods: ['GET'])]
    public function show(CarteBancaire $carteBancaire): Response
    {
        return $this->render('carte_bancaire/show.html.twig', [
            'carte_bancaire' => $carteBancaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_carte_bancaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CarteBancaire $carteBancaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarteBancaireType::class, $carteBancaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_carte_bancaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carte_bancaire/edit.html.twig', [
            'carte_bancaire' => $carteBancaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_carte_bancaire_delete', methods: ['POST'])]
    public function delete(Request $request, CarteBancaire $carteBancaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$carteBancaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($carteBancaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_carte_bancaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/carte-bancaire/freeze/{id}', name: 'carte_bancaire_freeze')]
    public function freeze(int $id, CarteBancaireRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $carteBancaire = $repository->find($id);
    
        if (!$carteBancaire) {
            throw $this->createNotFoundException('The card does not exist');
        }
    
        $carteBancaire->setIsFrozen(true);
        $entityManager->flush();
    
        // Redirect to the list page
        return $this->redirectToRoute('app_carte_bancaire_index');
    }
    

    #[Route('/carte-bancaire/unfreeze/{id}', name: 'carte_bancaire_unfreeze')]
public function unfreeze(int $id, CarteBancaireRepository $repository, EntityManagerInterface $entityManager): Response
{
    // Fetch the CarteBancaire entity by its ID
    $carteBancaire = $repository->find($id);

    // Check if the CarteBancaire entity was found
    if (!$carteBancaire) {
        // Throw a 404 Not Found exception if the card doesn't exist
        throw $this->createNotFoundException('No card found for id ' . $id);
    }

    // Set isFrozen to false to unfreeze the card
    $carteBancaire->setIsFrozen(false);

    // Persist changes to the database
    $entityManager->flush();

    // Redirect the user to a confirmation page or back to the card's detail page.
    // Replace 'carte_bancaire_show' with the actual route name of your card's detail page.
    // You might need to adjust the route name and parameters according to your application's routing.
    return $this->redirectToRoute('app_carte_bancaire_index');
}

}
