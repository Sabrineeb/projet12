<?php
namespace App\Controller;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;  // Assurez-vous d'importer la classe Response
use Symfony\Component\Routing\Annotation\Route;

class AjouterProduitController extends AbstractController
{
    #[Route('/ajouter/produit', name: 'app_ajouter_produit')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $produit = new Produit();
            $produit->setNom($request->request->get('nom'));
            $produit->setDescription($request->request->get('description'));
            $produit->setPrix((float) $request->request->get('prix'));

            // Utilisation de l'URL de l'image fournie par l'utilisateur
            $imageUrl = $request->request->get('image_url');
            if ($imageUrl) {
                $produit->setImage($imageUrl);  // Sauvegarder l'URL de l'image
            }

            // Sauvegarder le produit en base de données
            $entityManager->persist($produit);
            $entityManager->flush();

            // Message de succès
            $this->addFlash('success', 'Produit ajouté avec succès.');

            // Redirection vers la page d'accueil
            return $this->redirectToRoute('app_home');
        }

        // Retourne la vue si la méthode est GET ou en cas d'erreur
        return $this->render('ajouter_produit/ajouter.html.twig');
    }
}
