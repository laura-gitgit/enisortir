<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\AnnulationType;
use App\Form\ModifierSortieType;
use App\Form\SortieFormType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreationSortieController extends AbstractController
{

    #[Route('/creation', name: 'sortie_creation')]
    public function create(
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



        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid())
        {

            if (isset($request->get('sortie_form')['Enregistrer']))
            {
                try{
                $sortie->setEtat($etatRepository->findOneBy(['id' => 1]));
                $em->persist($sortie);
                $em->flush();

                }catch(\Exception $exception){
                    dd($exception->getMessage());
                }

                return $this->redirectToRoute('_sorties');


            } else if ( isset($request->get('sortie_form')['Publier'])){

                try{
                $sortie->setEtat($etatRepository->findOneBy(['id' => 2]));
                $em->persist($sortie);
                $em->flush();

                }catch(\Exception $exception){
                    dd($exception->getMessage());
                }

            return $this->redirectToRoute('_sorties');

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

#[Route('/modification/{id}', name:'sortie_modification')]
    public function modifierSortie(
    int                     $id,
    EtatRepository          $etatRepository,
    SortieRepository        $sortieRepository,
    Request                 $request,
    EntityManagerInterface  $em,

):Response
        {

            $sortieBase =$sortieRepository->findOneBy(['id'=>$id]);
            $sortie=$sortieBase;

            $modifierSortie = $this->createForm(ModifierSortieType::class, $sortie);
            $modifierSortie->handleRequest($request);

             if ($modifierSortie->isSubmitted()&& $modifierSortie->isValid()) {

                 $sortieBase->setSite($sortie->getSite());
                 $sortieBase->setNom($sortie->getNom());
                 $sortieBase->setDateHeureDebut($sortie->getDateHeureDebut());
                 $sortieBase->setDuree($sortie->getDuree());
                 $sortieBase->setDateLimiteInscription($sortie->getDateLimiteInscription());
                 $sortieBase->setNbInscriptionsMax($sortie->getNbInscriptionsMax());
                 $sortieBase->setInfosSortie($sortie->getInfosSortie());
                 $sortieBase->setOrganisateur($sortie->getOrganisateur());

                 if (isset($request->get('modifier_sortie')['Enregistrer']))
                 {
                    try{
                     $sortieBase->setEtat($etatRepository->findOneBy(['id' => 1]));

                     $em->persist($sortieBase);
                     $em->flush();
                     }catch (\Exception $exception){
                        dd($exception->getMessage($exception->getMessage()));
                    }
                     return $this->redirectToRoute('_main_sorties');

                 } elseif (isset($request->get('modifier_sortie')['Publier']))
                 {
                     try{
                     $sortie->setEtat($etatRepository->findOneBy(['id' => 2]));
                     $em->persist($sortieBase);
                     $em->flush();
                     }catch(\Exception $exception){
                         dd($exception->getMessage());
                     }
                 return $this->redirectToRoute('_main_sorties');

                 }elseif (isset($request->get('modifier_sortie')['Supprimer']))
                 {
                      try{
                     $em->remove($sortieBase);
                     $em->flush();

                      }catch (\Exception $exception){
                          dd($exception->getMessage());
                      }
                     return $this->redirectToRoute('_sorties');

                 }else{
                     return $this->redirectToRoute('_sorties');
                 }
             }


             return $this->render('sortie_modification.html.twig',
                compact('modifierSortie')
        );
}

#[Route('/annulation/{id}', name:'sortie_annulation')]
    public function annulationSortie(
        int                     $id,
        SortieRepository        $sortieRepository,
        EntityManager           $em,
        Request                 $request,
        Sortie                  $sortie,
        EtatRepository          $etatRepository
        ):Response
        {

        $sortieBase =$sortieRepository->findOneBy(['id'=>$id]);
        $sortie=$sortieBase;
        $annulationSortie = $this->createForm(AnnulationType::class, );
        $annulationSortie->handleRequest($request);


        if($annulationSortie->isSubmitted() && $annulationSortie->isValid())
        {
            $sortieBase->setInfosSortie($sortie->getInfosSortie());

            if (isset($request->get('modifier_sortie')['Enregistrer']))
            {
                try{
                    $sortie->setEtat($etatRepository->findOneBy(['id' => 5])); // TO DO verifier id etat

                    $em->persist($sortieBase);
                    $em->flush();
                }catch (\Exception $exception){
                    dd($exception->getMessage());
                }
                return $this->redirectToRoute('_sorties');

            }else{
                return $this->redirectToRoute('_sorties');
            }
        }

        return $this->render('sortie_annulation.html.twig',
        compact('annulationSortie')
        );

    }
}