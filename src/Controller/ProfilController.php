<?php

namespace App\Controller;

use App\Form\ModifProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profil', name: 'profil')]
class ProfilController extends AbstractController
{
    #[IsGranted('ROLE_USERACTIF')]
    #[Route('/details/{id}', name: '_details', requirements: ['id' => '\d+'])]
    public function details(
        int $id,
        UserRepository $userRepository
    ): Response
    {
        $user = $this->getUser();
        $profil = $userRepository->findOneBy(["id" => $id]);
        if ($user->getUserIdentifier() === $profil->getUserIdentifier()){
            return $this->redirectToRoute('profil_modif');
        }else{
        return $this->render(
            'profil/details.html.twig',
            compact('profil')
        );
        }
    }
    #[IsGranted('ROLE_USERACTIF')]
    #[Route('/modif', name: '_modif')]
    public function modif(
        UserRepository $userRepository,
        EntityManagerInterface $em,
        Request $request,
        UserPasswordHasherInterface $passwordEncoder

    ): Response
    {
        $user = $this->getUser();
        if (!$user){
            return $this->redirectToRoute('app_login');
        }else{
            $profil = $userRepository->findOneBy(["email" => $user->getUserIdentifier()]);
        }

        $profilForm = $this->createForm(ModifProfilType::class, $profil);
        $profilForm->handleRequest($request);

        if ($profilForm->isSubmitted() && $profilForm->isValid()){

//            if (!$passwordEncoder->isPasswordValid($profil, $profilForm->get('password')->getData()))
//            {
//                $this->addFlash('danger', 'Le mot de passe actuel est incorrect.');
//                return $this->redirectToRoute('profil_modif');
//            }
           $newPassword = $profilForm->get('password')['first']->getData();
            if ($newPassword === null){
                $newPassword = $profil->getPassword();
                $profil->setPassword($newPassword);
            }else {
                $hashedPassword = $passwordEncoder->hashPassword($profil, $newPassword);
                $profil->setPassword($hashedPassword);
            }
            $em->persist($profil);
            $em->flush();

            $this->addFlash('success', 'Profil modifié');
            return $this->redirectToRoute('_sorties');
        }
        return $this->render(
            'profil/modif.html.twig',
            compact('profilForm', 'profil')
        );
    }
}
