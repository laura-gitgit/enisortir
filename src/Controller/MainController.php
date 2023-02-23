<?php

namespace App\Controller;

use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MainController extends AbstractController
{
    /**
     * @param SortieRepository $sortieRepository
     * @param SiteRepository $siteRepository
     * @return Response
     */
//    #[IsGranted('ROLE_USER_ACTIF')]
    #[Route('/sorties', name: '_sorties')]
    public function sorties(SortieRepository $sortieRepository, SiteRepository $siteRepository) : Response
    {
        $sorties = $sortieRepository->findAll();
        $date = new \DateTime();
        $sites = $siteRepository->findAll();

        return $this->render('main/sorties.html.twig',
            compact('sorties',  'sites', 'date'));
    }

    /**
     * @param Request $request
     * @param SortieRepository $sortieRepository
     * @param SiteRepository $siteRepository
     * @return Response
     */
//    #[IsGranted('ROLE_USERACTIF')]
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
        $isOrganisateur = ($request->query->get('organisateur') == "on") ? true : false;
        $isInscrit = ($request->query->get('inscrit') == "on") ? true : false;
        $isNotInscrit = ($request->query->get('nonInscrit') == "on") ? true : false;
        $sortieTerminee = ($request->query->get('sortiesPassees') == "on") ? true : false;

        $sorties = $sortieRepository->findAllByAllParameters2($nomSite, $nomSortie, $debutSortie, $finSortie, $isOrganisateur, $userConnecte, $isInscrit, $isNotInscrit, $sortieTerminee);

        return $this->render('main/sorties.html.twig',
            compact('sorties', 'sites', 'date'));
    }
}
