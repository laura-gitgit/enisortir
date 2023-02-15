<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\ModifierSortieType;
use App\Form\SortieFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreationSortieController extends AbstractController
{

    #[Route('/creation/{id}', name: 'sortie_creation')]
    public function create(
      //  int $id,
       // User $organisateur_id,
        EntityManagerInterface $em,
        Request $request,

    ): Response
    {
        //instance de sortie
        $sortie = new Sortie();
        $sortie->setOrganisteur($this->getUser());
      //  $organisateur_id = $request->query->get('id');


        $sortieForm = $this->createForm(SortieFormType::class, $sortie);
        // formulaire creation
        $sortieForm->handleRequest($request);


        if ($request->query->get('enregister') != null) {
            dump($request->request->get('enregister'));
            if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

                $em->persist($sortie);
                $em->flush();
                return $this->render('sortie/creation.html.twig',
                    compact('sortieForm'),

                );

            }
        } elseif (($request->query->get('publier','publier') != null)) {

            if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
                $em->persist($sortie);
                $em->flush();
                return $this->render('sortie/creation.html.twig',
                    compact('sortieForm'),
                    dump($request)
                );
            }

        } else{

            return $this->render('sortie/creation.html.twig',
                compact('sortieForm')
            );
        }
        return $this->render('sortie/creation.html.twig',
            compact('sortieForm')
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
