<?php

namespace App\Services;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ListerSortie
{
    protected $entityManager;
    protected $security;

    /**
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function sorties(SortieRepository $sortieRepository, SiteRepository $siteRepository)
    {
        $sorties = $sortieRepository->findAll();
        $userConnecte = $this->security->getUser();
        $date = new \DateTime();
        $sites = $siteRepository->findAll();


        return $this->render('main/sorties.html.twig',
            compact('sorties', 'date', 'sites', 'nbInscrits', 'organisateur', 'etatSortie', 'isInscrit'));
    }


}