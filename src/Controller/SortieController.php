<?php

namespace App\Controller;

use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/inscription/{id}', name: '_inscriptionSortie')]
    public function inscriptionSortie($id, SiteRepository $siteRepository, SortieRepository $sortieRepository, EntityManagerInterface $em): Response
    {
        $userConnecte = $this->getUser();
        $sortie = $sortieRepository->findOneBy(['id'=>$id]);
        $date = new \DateTime('now');
        $sites = $siteRepository->findAll();

        if(count($sortie->getParticipants()) < $sortie->getNbInscriptionsMax() && $sortie->getDateLimiteInscription()<=$date){
             $sortie->addParticipant($userConnecte);
        }
        //TODO try catch et add flash
        $em->persist($sortie);
        $em->flush();
        $this->addFlash('success', 'Vous êtes bien inscris sur la sortie : '.$sortie->getNom());
        return $this->redirectToRoute('_sorties');
    }

    #[Route('/desinscription/{id}', name: '_desinscriptionSortie')]
    public function desinscriptionSortie($id, SiteRepository $siteRepository, SortieRepository $sortieRepository, EntityManagerInterface $em): Response
    {
        $userConnecte = $this->getUser();
        $sortie = $sortieRepository->findOneBy(['id'=>$id]);
        $sites = $siteRepository->findAll();

        $sortie->removeParticipant($userConnecte);

        //TODO try catch
        $em->persist($sortie);
        $em->flush();
        $this->addFlash('success', 'Vous vous êtes désinscris de la sortie : '.$sortie->getNom());
        return $this->redirectToRoute('_sorties');
    }
}
