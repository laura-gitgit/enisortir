<?php

namespace App\Controller;

use App\Entity\Site;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/main', name: '_main')]
class MainController extends AbstractController
{
    #[Route('/sorties', name: '_sorties')]
    public function sorties(SortieRepository $sortieRepository, SiteRepository $siteRepository) : Response
    {
            $sorties = $sortieRepository->findAll();
            $userConnecte = $this->getUser();
            $date = new \DateTime();
            $sites = $siteRepository->findAll();

            return $this->render('main/sorties.html.twig', compact('sorties', 'date', 'sites'));
    }
}
