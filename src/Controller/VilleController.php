<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\AjoutVilleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{
    #[Route('/ville/ajout', name: '_ajoutVille')]
    public function ajoutVille(EntityManagerInterface $em, Request $request): Response
    {
        $ville = new Ville();
        $villeForm = $this->createForm(AjoutVilleType::class, $ville);
        $villeForm->handleRequest($request);

        if($villeForm->isSubmitted() && $villeForm->isValid()){
            try {
                $em->persist($ville);
            } catch (\Exception $exception){
                dd($exception->getMessage());
            }
        }
        $em->flush();
        $this->addFlash('success', 'Ville ajoutée');
        return $this->render('sortie/creation.html.twig', compact('villeForm'));
    }
}
