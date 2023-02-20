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

    /**
     * @param Sortie|null $sortieBase
     * @param Sortie|null $sortie
     * @return void
     */
    public function extracted(?Sortie $sortieBase, ?Sortie $sortie): void
    {
        $sortieBase->setSite($sortie->getSite());
        $sortieBase->setNom($sortie->getNom());
        $sortieBase->setDateHeureDebut($sortie->getDateHeureDebut());
        $sortieBase->setDuree($sortie->getDuree());
        $sortieBase->setDateLimiteInscription($sortie->getDateLimiteInscription());
        $sortieBase->setNbInscriptionsMax($sortie->getNbInscriptionsMax());
        $sortieBase->setInfosSortie($sortie->getInfosSortie());
        $sortieBase->setOrganisateur($sortie->getOrganisateur());
    }


    /**
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param EtatRepository $etatRepository
     * @return Response
     */
    #[Route('/creation', name: 'sortie_creation')]
        public function create(
        EntityManagerInterface $em,
        Request                $request,
        EtatRepository         $etatRepository
     ): Response
    {
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

                $this->addFlash('success', 'La Sortie est crée.');
                }catch(\Exception $exception){
                    dd($exception->getMessage());
                }
                return $this->redirectToRoute('_sorties');

            } else if ( isset($request->get('sortie_form')['Publier'])){

                try{
                $sortie->setEtat($etatRepository->findOneBy(['id' => 2]));
                $em->persist($sortie);
                $em->flush();
                $this->addFlash('success', 'La Sortie est publiée.');

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
    /**
     * @param int $id
     * @param SortieRepository $sortieRepository
     * @return Response
     */
    #[Route('/detail/{id}', name: 'sortie_detail')]
        public function afficherSortie(
        int              $id,
        SortieRepository $sortieRepository
        ):Response
        {
            $sortie =$sortieRepository->findOneBy(['id'=>$id]);
            return $this->render('sortie/detail.html.twig',
                compact('sortie')
            );
}
    /**
     * @param int $id
     * @param EtatRepository $etatRepository
     * @param SortieRepository $sortieRepository
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
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

                 $this->extracted($sortieBase, $sortie);

                 switch ($request->get('modifier_sortie')!=null){

                     case (isset($request->get('modifier_sortie')['Enregistrer'])):
                    try{
                     $sortieBase->setEtat($etatRepository->findOneBy(['id' => 1]));
                     $em->persist($sortieBase);
                     $this->addFlash('success', 'La Sortie est modifiée et crée.');
                     }catch (\Exception $exception){
                        dd($exception->getMessage());
                    }
                    break;

                     case (isset($request->get('modifier_sortie')['Publier'])):
                     try{
                     $sortie->setEtat($etatRepository->findOneBy(['id' => 2]));
                     $em->persist($sortieBase);
                     $this->addFlash('success', 'La Sortie est modifiée et Publiée.');
                     }catch(\Exception $exception){
                         dd($exception->getMessage());
                     }
                     break;

                     case (isset($request->get('modifier_sortie')['Supprimer'])):
                      try{
                     $em->remove($sortieBase);
                     $this->addFlash('success', 'La Sortie est supprimée.');
                      }catch (\Exception $exception){
                          dd($exception->getMessage());
                      }
                      break;
                 }
               $em->flush();
                 return $this->redirectToRoute('_sorties');
             }
            return $this->render('sortie_modification.html.twig',
                compact('modifierSortie')
       );
}

    /**
     * @param int $id
     * @param SortieRepository $sortieRepository
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param EtatRepository $etatRepository
     * @return Response
     */
    #[Route('/annulation/{id}', name:'sortie_annulation')]
        public function annulationSortie(
        int                     $id,
        SortieRepository        $sortieRepository,
        EntityManagerInterface  $em,
        Request                 $request,
        EtatRepository          $etatRepository
        ):Response
        {

        $sortieBase =$sortieRepository->findOneBy(['id'=>$id]);
        $sortie=$sortieBase;
        $annulationSortie = $this->createForm(AnnulationType::class,$sortie );
        $annulationSortie->handleRequest($request);


        if($annulationSortie->isSubmitted() && $annulationSortie->isValid())
        {
            $this->extracted($sortieBase, $sortie);

            if (isset($request->get('annulation')['Enregistrer']))
            {

                try{
                    $sortieBase->setEtat($etatRepository->findOneBy(['id' => 6]));

                    $em->persist($sortieBase);
                    $em->flush();
                    $this->addFlash('success', 'La Sortie est annuléé.');

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