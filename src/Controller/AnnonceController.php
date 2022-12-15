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
        $form=$this->createForm(AnnonceType::class, $new_annonce);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $appUser = $this->getUser();
            $data=$form->getData();
            $new_annonce->setCreatedAt(new \DateTime());
            $new_annonce->setUpdatedAt(new \DateTime());
            $new_annonce->setUser($appUser);
            $entityManager->persist($data);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'L\'annonce a bien été postée !'
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
        $annonce = $entityManager->getRepository(Annonce::class)->annonceWithId($id);
        $user = $this->getUser();
        
        return $this->render('annonce/item.html.twig', [
            'controller_name' => 'AnnonceController',
            'annonce' => $annonce,
            'user' => $user
        ]);
    }

    #[Route('/annonce/modifier/{id?}', name: 'modifier_annonce')]
    public function modifierAnnonce(ManagerRegistry $doctrine,Request $request, int $id): Response{
        $entityManager = $doctrine->getManager();
        $annonce = $entityManager->getRepository(Annonce::class)->find($id);
        
        if($annonce->getUser() != $this->getUser()){
            return $this->redirectToRoute('home_page');
        }
        
        $form=$this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data=$form->getData();
            $annonce->setUpdatedAt(new \DateTime());
            $entityManager->persist($data);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'Changement sauvegardé !'
            );
            return $this->redirectToRoute('user_annonce', [
                'id' => $this->getUser()->getId()
            ]);
        }
        return $this->renderForm('annonce/modifier.html.twig',[
            'form'=>$form
        ]);
    }

    #[Route('/annonce/supprimer/{id?}', name: 'supprimer_annonce')]
    public function supprimerAnnonce(ManagerRegistry $doctrine,Request $request, int $id): Response{
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(Annonce::class)->find($id);
        $annonce = $entityManager->getRepository(Annonce::class)->supprimer($id);

        if($user->getUser()->getId() != $this->getUser()){
            return $this->redirectToRoute('home_page');
        }

        $form=$this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data=$form->getData();
            $entityManager->persist($data);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'Changement sauvegardé !'
            );
            return $this->redirectToRoute('user_annonce', [
                'id' => $this->getUser()->getId()
            ]);
        }
        return $this->renderForm('annonce/modifier.html.twig',[
            'form'=>$form
        ]);
    }

    #[Route('/user/{id?}', name: 'user_annonce')]
    public function annonceOfUser(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        // on fait la requete dans la fonction AnnonceRepository
        $annonce = $entityManager->getRepository(Annonce::class)->annonceOfUser($id);


        return $this->render('annonce/user.html.twig', [
            'controller_name' => 'AnnonceController',
            'annonce' => $annonce,
            'user' => $this->getUser()
        ]);
    }
}
