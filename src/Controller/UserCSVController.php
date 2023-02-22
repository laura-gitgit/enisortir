<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserCSVType;
use App\Repository\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserCSVController extends AbstractController
{
    #[Route('/user', name: '_fichierCSV')]
    public function fichierCSV(
        Request $request,
        EntityManagerInterface $em,
        SiteRepository $siteRepository,
        UserPasswordHasherInterface $passwordHasher): Response
    {
        $userCSVForm = $this->createForm(UserCSVType::class);
        $userCSVForm->handleRequest($request);

        if ($userCSVForm->isSubmitted() ) {
            $csvFile = $userCSVForm['fichier']->getData();

            $spreadsheet = IOFactory::load($csvFile);

            $feuilleCsv = $spreadsheet->getActiveSheet();

            $tabUsers = $feuilleCsv->toArray(true, true, true, true);
            $users = new ArrayCollection();

            foreach ($tabUsers as $cle => $utilisateur) {
                if($cle == 1)
                {
                    continue;
                }
                $site = $siteRepository->find($utilisateur['F']);

                $newUser = new User();
                $newUser->setEmail($utilisateur['A']);
                $newUser->setSite($site);
                $newUser->setNom($utilisateur['C']);
                $newUser->setPrenom($utilisateur['D']);
                $newUser->setTelephone($utilisateur['E']);

                $newUser->setActif(true);
                $newUser->setAdministrateur(false);
                $newUser->setUpdatedAt(new \DateTime('now'));
                $newUser->setRoles(['ROLE_USER']);
                $newUser->setPseudo(' ');

                $mdpHache = $passwordHasher->hashPassword($newUser, $utilisateur['B']);
                $newUser->setPassword($mdpHache);

                $users->add($newUser);
            }

            if($userCSVForm->isValid()){
                try {
                    foreach ($users as $user) {
                        $em->persist($user);
                    }
                    $em->flush();
                    $this->addFlash('success', 'Utilisateurs importés avec succès');
                    return $this->redirectToRoute('_sorties');
                } catch (\Exception $exception) {
                    dd($exception->getMessage());
                }
            }

        }

        return $this->render('user/import.html.twig',
            compact('userCSVForm'));
    }
}



