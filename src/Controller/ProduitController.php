<?php

namespace App\Controller;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/produits/{id}', name: 'produit_detail')]
    public function detail(int $id): Response
    {
        $produit = $this->entityManager->getRepository(Produit::class)->find($id);
        
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }
        
        return $this->render('produit/detail.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/produits/{id}/acheter', name: 'produit_acheter')]
    public function acheter(int $id, Request $request): Response
    {
        $produit = $this->entityManager->getRepository(Produit::class)->find($id);
        
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }

        // Récupérer la session
        $session = $request->getSession();

        // Récupérer le panier actuel ou un tableau vide si le panier n'existe pas
        $panier = $session->get('panier', []);

        // Ajouter ou mettre à jour le produit dans le panier avec la quantité
        $produitTrouve = false;
        foreach ($panier as &$item) {
            if ($item['id'] === $produit->getId()) {
                $item['quantite'] += 1; // Augmenter la quantité si le produit existe déjà
                $produitTrouve = true;
                break;
            }
        }

        // Si le produit n'est pas dans le panier, l'ajouter avec une quantité de 1
        if (!$produitTrouve) {
            $panier[] = [
                'id' => $produit->getId(),
                'nom' => $produit->getNom(),
                'prix' => $produit->getPrix(),
                'quantite' => 1,
            ];
        }

        // Sauvegarder le panier dans la session
        $session->set('panier', $panier);

        // Passer le panier au template
        return $this->render('produit/achat.html.twig', [
            'produits' => $panier,  // Afficher les produits dans le panier avec leur quantité
        ]);
    }
}
