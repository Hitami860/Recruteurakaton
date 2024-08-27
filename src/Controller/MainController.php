<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(EntityManagerInterface $entity): Response
    {
        $annonces = $entity->getRepository(Annonce::class)->findAll();
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'annonces' => $annonces,
        ]);
    }

    #[Route('/annonce/{id}', name: 'app_annonce')]
    public function annonce($id, EntityManagerInterface $entity): Response
    {
        $annonces = $entity->getRepository(Annonce::class)->findAll();
        $annonce = $entity->getRepository(Annonce::class)->findOneBy(['id' => $id]);
        return $this->render('annonce/annonce.html.twig', [
            'controller_name' => 'MainController',
            'id' => $id,
            'annonces' => $annonces,
        ]);
    }


    #[Route('/annonce/n/new', name: 'app_annonce_new')]
    public function newAnnonce(Request $request, EntityManagerInterface $entity): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity->flush();
            $entity->persist($annonce);
        }


        return $this->render('annonce/newAnnonce.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
