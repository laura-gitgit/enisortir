<?php

namespace App\Controller\Admin;

use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractDashboardController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(UserCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Enisortir - Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Accueil du site', 'fas fa-home', '_sorties');

        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Afficher les utilisateurs', 'fas fa-eye', User::class);
        yield MenuItem::linkToCrud('Ajouter un utilisateur', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW);

        yield MenuItem::section('Sorties');
        yield MenuItem::linkToCrud('Afficher les sorties', 'fas fa-eye', Sortie::class);

        yield MenuItem::section('Villes');
        yield MenuItem::linkToCrud('Afficher les villes', 'fas fa-eye', Ville::class);
        yield MenuItem::linkToCrud('Ajouter une ville', 'fas fa-plus', Ville::class)->setAction(Crud::PAGE_NEW);

        yield MenuItem::section('Sites');
        yield MenuItem::linkToCrud('Afficher les sites', 'fas fa-eye', Site::class);
        yield MenuItem::linkToCrud('Ajouter un site', 'fas fa-plus', Site::class)->setAction(Crud::PAGE_NEW);

    }
}
