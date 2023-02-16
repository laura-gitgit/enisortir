<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\ModifierSortieType;
use App\Form\SortieFormType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreationSortieController extends AbstractController
{
//TO DO integrer la ville dans le formulaire.
    #[Route('/creation', name: 'sortie_creation')]
    public function create(
        //  int $id,
        // User $organisateur_id,
        EntityManagerInterface $em,
        Request                $request, EtatRepository $etatRepository

    ): Response
    {
        //instance de sortie
        $sortie = new Sortie();
        $sortie->setDateHeureDebut(new \DateTime());
        $user = $this->getUser();
        $sortie->setOrganisateur($user);
        $sortie->setSite($user->getSite());

        $sortieForm = $this->createForm(SortieFormType::class, $sortie);
       // $sortieForm->get('ville')->get('nom')->getData();                   //Recuperer les datas du nom de la ville.

        // formulaire creation
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid())
        {
            if (isset($request->get('sortie_form')['Enregistrer']))
            {
                $sortie->setEtat($etatRepository->findOneBy(['id' => 1]));
                $em->persist($sortie);
                $em->flush();
                return $this->redirectToRoute('_main_sorties');

            } else if ( isset($request->get('sortie_form')['Publier'])){
                $sortie->setEtat($etatRepository->findOneBy(['id' => 2]));
                $em->persist($sortie);
                $em->flush();
                return $this->redirectToRoute('_main_sorties');
            }
        }

        return $this->render('sortie/creation.html.twig',
            compact('sortieForm')
        );
    }


#[Route('/detail/{id}', name: 'sortie_detail')]
    public function afficherSortie(
        int $id,
    SortieRepository $sortieRepository
):Response
        {
            $sortie =$sortieRepository->findOneBy(['id'=>$id]);
            return $this->render('sortie/detail.html.twig',
                compact('sortie')
            );

}


//    // Annuler une sortie.
//        #[Route('/annuler/{id}', name: 'sortie_annuler')]
//    public function annulerSortie(
//        int $id,
//        Sortie $sortie,
//        EntityManagerInterface $em
//    ):RedirectResponse
//    {
//
//        $sortie->setEtat('Annulee');
//        $em->persist($sortie);
//        $em->flush();
//        return $this->redirectToRoute('_main_sorties');
//    // TO DO verifier routes et appeles de fonction, voir avec Laura.
//    // Gerer les etats en bases
//
//    }

    //modification de la sortie
//    #[Route('/modifier/{id}', name: 'sortie_modifier')]
//    public function modifierSortie(
//        Sortie $sortie,
//        EntityManagerInterface $em,
//        Request $request
//        ):RedirectResponse
//    {
//
//        $modifForm = $this-> createForm(ModifierSortieType::class, $sortie);
//        $modifForm->handleRequest($request);
//
//        if($modifForm->isSubmitted() && $modifForm->isValid())
//        {
//            $em->persist($sortie);
//            $em->flush();
//            return $this->redirectToRoute('_main_sorties');
//        }
//    }





}
