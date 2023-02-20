<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuNomType;
use App\Form\LieuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/lieu/ajout', name: 'lieu_ajoutLieu')]
    public function ajoutLieu(Request $request, EntityManagerInterface $em): Response
    {
        $lieu = new Lieu();
        $lieuForm = $this->createForm(LieuType::class);
        $lieuForm->handleRequest($request);

        if($lieuForm->isSubmitted() && $lieuForm->isValid()){
            try {
                $em->persist($lieu);
            }catch (\Exception $exception){
                dd($exception->getMessage());
            }

            $em->flush();
            $this->addFlash('success', 'Lieu ajouté avec succès');
            $this->redirectToRoute('sortie_creation');
        }

        return $this->render('lieu/ajout.html.twig', compact('lieuForm'));
    }

    #[Route('/lieu/ajoutNom', name: 'lieu_ajoutNomLieu')]
    public function ajoutNomLieu(Request $request, EntityManagerInterface $em): Response
    {
        $lieu = new Lieu();
        $lieuForm = $this->createForm(LieuNomType::class);
        $lieuForm->handleRequest($request);

        if($lieuForm->isSubmitted() && $lieuForm->isValid()){
            try {
                $em->persist($lieu);
            }catch (\Exception $exception){
                dd($exception->getMessage());
            }

            $em->flush();
            $this->addFlash('success', 'Lieu ajouté avec succès');
            $this->redirectToRoute('sortie_creation');
        }

        return $this->render('lieu/ajout.html.twig', compact('lieuForm'));
    }
}
