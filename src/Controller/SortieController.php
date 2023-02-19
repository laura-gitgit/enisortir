<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/inscription/{id}', name: '_inscriptionSortie')]
    public function inscriptionSortie($id, SortieRepository $sortieRepository, EntityManagerInterface $em): Response
    {
        $userConnecte = $this->getUser();
        $sortie = $sortieRepository->findOneBy(['id'=>$id]);

        if(count($sortie->getParticipants()) < $sortie->getNbInscriptionsMax()){
             $sortie->addParticipant($userConnecte);
        }
        //TODO try catch et add flash
        $em->persist($sortie);
        $em->flush();
        return $this->render('sortie/detail.html.twig', [
            compact('sortie'),
        ]);
    }

    #[Route('/desinscription/{id}', name: '_desinscriptionSortie')]
    public function desinscriptionSortie($id, SortieRepository $sortieRepository, EntityManagerInterface $em): Response
    {
        $userConnecte = $this->getUser();
        $sortie = $sortieRepository->findOneBy(['id'=>$id]);
        $sortie->removeParticipant($userConnecte);

        //TODO try catch
        $em->persist($sortie);
        $em->flush();
        return $this->render('sortie/detail.html.twig', compact('sortie'));
    }
}
