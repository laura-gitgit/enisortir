<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\User;
use App\Form\UserCSVType;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserCSVController extends AbstractController
{
    #[Route('/user', name: '_fichierCSV')]
    public function fichierCSV(Request $request, EntityManagerInterface $em): Response
    {
        $userCSVForm = $this->createForm(UserCSVType::class);
        $userCSVForm->handleRequest($request);

        if($userCSVForm->isSubmitted()){
            $csvFile = $userCSVForm['fichier']->getData();

            $spreadsheet = IOFactory::load($csvFile);
            $feuilleCsv = $spreadsheet->getActiveSheet();

            $tabUsers = $feuilleCsv->toArray(true, true, true, true);
            foreach ($tabUsers as $user) {
                $nouvelUtilisateur = [
                'email' => ($user['A']),
                    'password'=> ($user['B']),
                    'nom' => ($user['C']),
                    'prenom' => ($user['D']),
                    'telephone' => ($user['E']),
                    'site' => ($user['F']),
                ];
            }

            $siteUser = new Site();
            $siteUser->setNom($nouvelUtilisateur['site']);
            $newUser = new User();
            $newUser->setEmail($nouvelUtilisateur['email']);
            $newUser->setSite($siteUser);
            $newUser->setNom($nouvelUtilisateur['nom']);
            $newUser->setPrenom($nouvelUtilisateur['prenom']);
            $newUser->setTelephone($nouvelUtilisateur['telephone']);
            $newUser->setPassword($nouvelUtilisateur['password']);
            $newUser->setActif(true);
            $newUser->setAdministrateur(false);
            $newUser->setUpdatedAt(new \DateTime('now'));

            try {

                $em->persist($newUser);
            }catch(\Exception $exception){
                dd($exception->getMessage());
            }
            $em->flush();
        }
        return $this->render('user/import.html.twig',
            compact('userCSVForm'));
    }
}
