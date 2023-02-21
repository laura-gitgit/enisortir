<?php

namespace App\Controller;

use App\Entity\Site;
use App\Repository\EtatRepository;
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

    /**
     * @param SortieRepository $sortieRepository
     * @param SiteRepository $siteRepository
     * @return Response
     */
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
