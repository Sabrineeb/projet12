<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PageController extends AbstractController
{
    #[Route('/accueil', name: 'app_pages')]
    public function index(): Response
    {
        
        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController', 
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('page/about.html.twig');
    }
    


}
