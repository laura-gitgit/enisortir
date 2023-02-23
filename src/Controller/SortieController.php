<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SortieController extends AbstractController
{

    /**
     * @param $id
     * @param SortieRepository $sortieRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/inscription/{id}', name: '_inscriptionSortie')]
    public function inscriptionSortie($id, SortieRepository $sortieRepository, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $sortie = $sortieRepository->findOneBy(['id' => $id]);
        $date = new \DateTime('now');

        if (count($sortie->getParticipants()) < $sortie->getNbInscriptionsMax() && $sortie->getDateLimiteInscription() >= $date) {
            $sortie->addParticipant($user);
        }
        try {
            $em->persist($sortie);
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }

        $em->flush();
        $this->addFlash('success', 'Vous êtes bien inscris sur la sortie : ' . $sortie->getNom());
        return $this->redirectToRoute('_sorties');
    }

    /**
     * @param $id
     * @param SortieRepository $sortieRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/desinscription/{id}', name: '_desinscriptionSortie')]
    public function desinscriptionSortie($id, SortieRepository $sortieRepository, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $sortie = $sortieRepository->findOneBy(['id' => $id]);
        $sortie->removeParticipant($user);

        try {
            $em->persist($sortie);
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }

        $em->flush();
        $this->addFlash('success', 'Vous vous êtes désinscris de la sortie : ' . $sortie->getNom());
        return $this->redirectToRoute('_sorties');
    }
}
