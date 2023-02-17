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

//#[Route('/', name: 'main')]
class MainController extends AbstractController
{
//    #[Route('/de', name: '_home')]
//    public function home(): Response
//    {
//        return $this->render('security/login.html.twig');
//    }

    #[Route('/sorties', name: '_sorties')]
    public function sorties(SortieRepository $sortieRepository, SiteRepository $siteRepository) : Response
    {
        $userConnecte = $this->getUser();
        $sorties = $sortieRepository->findAll();

        $date = new \DateTime();
        $sites = $siteRepository->findAll();

        return $this->render('main/sorties.html.twig',
            compact('sorties',  'sites', 'date'));
    }

    #[Route('/tri', name: '_tri')]
    public function sortiesTriees (Request $request, SortieRepository $sortieRepository, SiteRepository $siteRepository): Response
    {
        $userConnecte = $this->getUser();
        $sites = $siteRepository->findAll();
        $date = new \DateTime();
        $nomSite = $request->query->get('site');
        $nomSortie = $request->query->get('nomSortie');
        $debutSortie = $request->query->get('debutSortie');
        $finSortie = $request->query->get('finSortie');
        $organisateur = $request->query->get('organisateur');
        $inscrit = $request->query->get('inscrit');
        $nonInscrit = $request->query->get('nonInscrit');
        $sortiePassee = $request->query->get('sortiesPassees');
        $rechercher = $request->query->get('validerRechercher');
        $isOrganisateur = false;
        $isInscrit = false;
        $isNotInscrit = false;
        $sortieTerminee = false;

        if ($organisateur == "on"){
            $isOrganisateur = true;
        }
        if ($inscrit == "on"){
            $isInscrit = true;
        }
        if ($nonInscrit == "on"){
            $isNotInscrit = true;
        }
        if ($sortiePassee == "on"){
            $sortieTerminee = true;
        }

        $sorties = $sortieRepository->findAllByAllParameters2($nomSite, $nomSortie, $debutSortie, $finSortie, $isOrganisateur, $userConnecte, $isInscrit, $isNotInscrit, $sortieTerminee);

//      dd($sorties);


//        if($site) {
//            $sorties = $sortieRepository->findAllBySite($site);
////            $sorties = $sortieRepository->findAllByAllParameters2($site, $nomSortie, $debutSortie, $finSortie, $isOrganisateur, $userConnecte, $isInscrit, $isNotInscrit, $sortieTerminee);
//        }
//        if ($site && $nomSortie){
////            $sorties = $sortieRepository->findAllByNameAndSite($site, $nomSortie);
//            $sorties = $sortieRepository->findAllByAllParameters2($site, $nomSortie, $debutSortie, $finSortie, $isOrganisateur, $userConnecte, $isInscrit, $isNotInscrit, $sortieTerminee);
//
//        }
//        if($site && $debutSortie && $finSortie){
////            $sorties = $sortieRepository->findAllBySiteNameAndDate($site, $debutSortie, $finSortie);
//            $sorties = $sortieRepository->findAllByAllParameters2($site, $nomSortie, $debutSortie, $finSortie, $isOrganisateur, $userConnecte, $isInscrit, $isNotInscrit, $sortieTerminee);
//
//        }
//        if($site && $nomSortie && $debutSortie && $finSortie){
////            $sorties = $sortieRepository->findAllByAllParameters($site, $nomSortie, $debutSortie, $finSortie);
//            $sorties = $sortieRepository->findAllByAllParameters2($site, $nomSortie, $debutSortie, $finSortie, $isOrganisateur, $userConnecte, $isInscrit, $isNotInscrit, $sortieTerminee);
//        }
//
//        if($site && $isOrganisateur){
////            $sorties = $sortieRepository->findAllByIsOrganisateur($site, $userConnecte);
//            $sorties = $sortieRepository->findAllByAllParameters2($site, $nomSortie, $debutSortie, $finSortie, $isOrganisateur, $userConnecte, $isInscrit, $isNotInscrit, $sortieTerminee);
//        }
//
//        if($site  && $nomSortie && $isOrganisateur){
//            $sorties = $sortieRepository->findAllByAllParameters2($site, $nomSortie, $debutSortie, $finSortie, $isOrganisateur, $userConnecte, $isInscrit, $isNotInscrit, $sortieTerminee);
//        }
//
//        if($site && $nomSortie&& $debutSortie && $finSortie && $isOrganisateur){
//            $sorties = $sortieRepository->findAllByAllParameters2($site, $nomSortie, $debutSortie, $finSortie, $isOrganisateur, $userConnecte, $isInscrit, $isNotInscrit, $sortieTerminee);
//
//        }
//
//


        return $this->render('main/sorties.html.twig',
            compact('sorties', 'sites', 'date'));
    }
}
