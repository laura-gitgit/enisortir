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
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use function Symfony\Component\String\u;

class CreationSortieController extends AbstractController
{

    /**
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param EtatRepository $etatRepository
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/creation', name: 'sortie_creation')]
        public function create(
        EntityManagerInterface $em,
        Request                $request,
        EtatRepository         $etatRepository
     ): Response
    {
        $sortie = new Sortie();
        $sortie->setDateHeureDebut(new \DateTime());
        /** @var ?User $user */
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
                $sortie->setEtat($etatRepository->find( 1));
                $em->persist($sortie);
                $em->flush();

                $this->addFlash('success', 'La Sortie est crée.');
                }catch(\Exception $exception){
                    dd($exception->getMessage());
                }
                return $this->redirectToRoute('_sorties');

            } else if ( isset($request->get('sortie_form')['Publier'])){
                try{
                $sortie->setEtat($etatRepository->find(2));
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
    #[IsGranted('ROLE_USER')]
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
     * @param Sortie $sortie
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
   #[IsGranted('ROLE_USER')]
    #[Route('/modification/{id}', name:'sortie_modification')]
        public function modifierSortie(
        Sortie                  $sortie,
        Request                 $request,
        EntityManagerInterface  $em,
        ):Response
        {
            $modifierSortie = $this->createForm(ModifierSortieType::class, $sortie);
            $modifierSortie->handleRequest($request);

             if ($modifierSortie->isSubmitted()&& $modifierSortie->isValid()) {
                 $etatRepository=$em->getRepository(Etat::class);

                 /** @var Form $modifierSortie */
                 switch ($modifierSortie->getClickedButton()->getName()){

                     case 'Enregistrer':
                    try{
                     $sortie->setEtat($etatRepository->find( 1));
                     $this->addFlash('success', 'La Sortie est modifiée et crée.');
                     }catch (\Exception $exception){
                        dd($exception->getMessage());
                    }
                    break;

                     case'Publier':
                     try{
                     $sortie->setEtat($etatRepository->find(2));
                     $this->addFlash('success', 'La Sortie est modifiée et Publiée.');
                     }catch(\Exception $exception){
                         dd($exception->getMessage());
                     }
                     break;

                     case 'Supprimer':
                      try{
                     $em->remove($sortie);
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
     * @param Sortie $sortie
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     *
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/annulation/{id}', name:'sortie_annulation')]
        public function annulationSortie(
        Sortie                  $sortie,
        EntityManagerInterface  $em,
        Request                 $request,

        ):Response
        {
        $annulationSortie = $this->createForm(AnnulationType::class,$sortie );
        $annulationSortie->handleRequest($request);

        if($annulationSortie->isSubmitted() && $annulationSortie->isValid())
        {
                try{
                    $etatRepository=$em->getRepository(Etat::class);
                    $sortie->setEtat($etatRepository->find(6));
                    $em->flush();
                    $this->addFlash('success', 'La Sortie est annuléé.');
                }catch (\Exception $exception){
                    dd($exception->getMessage());
                }
                return $this->redirectToRoute('_sorties');
        }
        return $this->render('sortie_annulation.html.twig',
        compact('annulationSortie')
        );
    }
}