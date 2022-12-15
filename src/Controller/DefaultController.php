<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Categorie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    //Page d'accueil
    #[Route('/home', name: 'home_page')]
    public function racine(): Response
    {
        return $this->redirectToRoute('racine_page');
    }
    
    //Redirection de la racine vers la page d'accueil
    #[Route('/', name: 'racine_page')]
    public function home(ManagerRegistry $doctrine,Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        // on fait la requete dans la fonction AnnonceRepository
        $annonces = $entityManager->getRepository(Annonce::class)->getAllAnnonce();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'DefaultController',
            'annonces' => $annonces
        ]);
    }
}
