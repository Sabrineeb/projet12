<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProduitRepository $produitRepository): Response
    {
        $produit = $produitRepository->findOneBy([], ['id' => 'DESC']); // dernier produit ajouté

        return $this->render('page/index.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('page/about.html.twig');
    }

    #[Route('/produit/ajouter', name: 'produit_ajouter')]
    public function ajouter(): Response
    {
        return $this->render('produit/ajouter.html.twig');
    }

    #[Route('/produit/supprimer/{id}', name: 'produit_supprimer')]
    public function supprimer($id, ProduitRepository $produitRepository, EntityManagerInterface $entityManager): Response
    {
        $produit = $produitRepository->find($id);

        if ($produit) {
            $entityManager->remove($produit);
            $entityManager->flush();
            $this->addFlash('success', 'Produit supprimé avec succès.');
        }

        return $this->redirectToRoute('app_home');
    }
}
