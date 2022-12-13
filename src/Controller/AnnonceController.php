<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    #[Route('/nouvelle_annonce', name: 'deposer_annonce_page')]
    public function nouvelleAnnonce(ManagerRegistry $doctrine,Request $request): Response
    {
        $new_annonce = new Annonce;
        $entityManager = $doctrine->getManager();
        // on fait la requete dans la fonction UtilisateursRepository
        // $user = $entityManager->getRepository(Utilisateurs::class);
        $form=$this->createForm(AnnonceType::class, $new_annonce);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data=$form->getData();
            $new_annonce->setCreatedAt(new \DateTime());
            $new_annonce->setUpdatedAt(new \DateTime());
            $entityManager->persist($data);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'User saved !'
            );
            return $this->redirectToRoute('annonce_page', [
                'form' => $form
            ]);
        }
        return $this->renderForm('annonce/nouvelle_annonce.html.twig',[
            'form'=>$form
        ]);
    }

    #[Route('/annonce', name: 'annonce_page')]
    public function annonce(ManagerRegistry $doctrine,Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $annonce = $entityManager->getRepository(Annonce::class);
        $annonce = $annonce->findAll();
        return $this->render('annonce/index.html.twig', [
            'annonce' => $annonce
        ]);
    }

    #[Route('/annonce/{id?}', name: 'annonce_item')]
    public function annonceWithId(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        // on fait la requete dans la fonction AnnonceRepository
        $annonce = $entityManager->getRepository(Annonce::class)->getId($id);
        
        return $this->render('annonce/item.html.twig', [
            'controller_name' => 'AnnonceController',
            'annonce' => $annonce
        ]);
    }
}
