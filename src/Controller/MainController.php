<?php

namespace App\Controller;

use App\Entity\Site;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Services\ListerSortie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: '_main')]
class MainController extends AbstractController
{

//    #[Route('/', name: 'main_home')]
//    public function home(): Response
//    {
//        return $this->render('security/login.html.twig');
//    }

    #[Route('/sorties', name: '_sorties')]
    public function sorties(SortieRepository $sortieRepository, SiteRepository $siteRepository) : Response
    {
        $sorties = $sortieRepository->findAll();
        $userConnecte = $this->getUser();
        $date = new \DateTime();
        $sites = $siteRepository->findAll();

        return $this->render('main/sorties.html.twig',
            compact('sorties',  'sites'));
    }

    #[Route('/tri', name: '_tri')]
    public function sortiesTriees (Request $request, SortieRepository $sortieRepository, SiteRepository $siteRepository): Response
    {
        $sites = $siteRepository->findAll();

        $site = $request->query->get('site');
        $nomSortie = $request->query->get('nomSortie');
        $debutSortie = $request->query->get('debutSortie');
        $finSortie = $request->query->get('finSortie');
        $isOrganisateur = $request->query->get('organisateur');

        if($site) {
            $sorties = $sortieRepository->findAllBySite($site);
        }
        if ($site && $nomSortie){
            $sorties = $sortieRepository->findAllByNameAndSite($site, $nomSortie);
        }
        if($site && $debutSortie && $finSortie){
            $sorties = $sortieRepository->findAllBySiteNameAndDate($site, $debutSortie, $finSortie);
        }
        if($site && $nomSortie && $debutSortie && $finSortie){
            $sorties = $sortieRepository->findAllByAllParameters($site, $nomSortie, $debutSortie, $finSortie);
        }

        if($site && $isOrganisateur){

        }

        return $this->render('main/sorties.html.twig',
            compact('sorties', 'sites'));
    }
}
