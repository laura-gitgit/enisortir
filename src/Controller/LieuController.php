<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LieuController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param VilleRepository $villeRepository
     * @return Response
     **/
    #[IsGranted('ROLE_USER')]
    #[Route('/lieu/ajout', name: 'lieu_ajoutLieu')]
    public function ajoutLieu(Request $request, EntityManagerInterface $em, VilleRepository $villeRepository): Response
    {
        $lieu = new Lieu();
        $villes = $villeRepository->findAll();
        $lieuForm = $this->createForm(LieuType::class, $lieu);
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

        return $this->render('lieu/ajout.html.twig', compact('lieuForm', 'villes'));
    }

}
