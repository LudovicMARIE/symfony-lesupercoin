<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonces;
use App\Form\AnnonceAddType;
use Symfony\Component\HttpFoundation\Request;

class AnnoncesController extends AbstractController
{
    #[Route('/annonces', name: 'app_annonces')]
    public function index(): Response
    {
        



        return $this->render('annonces/index.html.twig', [
            'controller_name' => 'AnnoncesController',

            
        ]);
    }

    #[Route('/annonces/add', name:'annonces_add')]
    public function annonces_add(ManagerRegistry $doctrine, Request $request){

        $this->denyAccessUnlessGranted('ROLE_USER');
        $entityManager = $doctrine->getManager();
        $annonce = new Annonces();
        $annonce->setCreatedat(new \DateTimeImmutable());

        $form = $this->createForm(AnnonceAddType::class, $annonce);

        $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $entityManager->persist($annonce);
                $entityManager->flush();
                return $this->redirectToRoute('app_home');
            }


        

        return $this->renderForm('annonces/addAnnonce.html.twig', [
            'formAnnonce' => $form,
        ]);


    }

    #[Route('/annonces/edit/{id}', name:'annonces_edit')]
    public function annonces_edit(ManagerRegistry $doctrine, Request $request, $id){
        $entityManager = $doctrine->getManager();

        $annonce = $doctrine->getRepository(Annonces::class)->find($id);
        $annonce->setUpdatedat(new \DateTimeImmutable());

        $form = $this->createForm(AnnonceAddType::class, $annonce);

        $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $entityManager->persist($annonce);
                $entityManager->flush();
                return $this->redirectToRoute('app_home');
            }


        

        return $this->renderForm('annonces/editAnnonce.html.twig', [
            'formAnnonce' => $form,
        ]);


    }

    #[Route('annonces/details/{id}', name: 'annonces_details')]
    public function details($id, ManagerRegistry $doctrine)
    {
        $annonce = $doctrine->getRepository(Annonces::class)->find($id);
        return $this->render('annonces/details.html.twig', [
            'annonce'=>$annonce
        ]);

    }

    #[Route('annonces/delete/{id}', name: 'annonces_delete')]
    public function delete($id, ManagerRegistry $doctrine)
    {
        $annonce = $doctrine->getRepository(Annonces::class)->find($id);
        $entityManager = $doctrine->getManager();
        $entityManager->remove($annonce);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }



}
