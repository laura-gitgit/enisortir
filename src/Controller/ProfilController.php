<?php

namespace App\Controller;

use App\Entity\User;
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
    /**
     * @param User $user
     * @return Response
     */
  #[IsGranted('ROLE_USER')]
    #[Route('/details/{id}', name: '_details', requirements: ['id' => '\d+'])]
    public function details(
        User $user,
    ): Response
    {
        if ($user) {
            return $this->redirectToRoute('profil_modif');
        } else {
            return $this->render(
                'profil/details.html.twig',
                compact('user')
            );
        }
    }

    /**
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordEncoder
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/modif', name: '_modif')]
    public function modif(
        UserRepository              $userRepository,
        EntityManagerInterface      $em,
        Request                     $request,
        UserPasswordHasherInterface $passwordEncoder

    ): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else {
            $profil = $userRepository->findOneBy(["email" => $user->getUserIdentifier()]);
        }

        $profilForm = $this->createForm(ModifProfilType::class, $profil);
        $profilForm->handleRequest($request);

        if ($profilForm->isSubmitted() && $profilForm->isValid()) {
            $newPassword = $profilForm->get('password')['first']->getData();
            if ($newPassword === null) {
                $newPassword = $profil->getPassword();
                $profil->setPassword($newPassword);
            } else {
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
