<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreationSortieController extends AbstractController
{
    #[Route('/creation', name: 'sortie_creation')]
    public function create(
        EntityManagerInterface $em,
        Request $request
    ): Response
    {
        //instance de sortie
        $sortie = new Sortie();

        $sortieForm = $this->createForm(SortieFormType::class, $sortie);
        // formulaire creation
        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){
            $em->persist($sortie);
            $em->flush();
        }

        return $this->render('sortie/creation.html.twig',
            compact('sortieForm')
        );

    }
}
