<?php

namespace App\Controller\Admin;

use App\Entity\Sortie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;

class SortieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sortie::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            Field::new('nom'),
            Field::new('dateHeureDebut'),
            Field::new('duree'),
            Field::new('dateLimiteInscription'),
            Field::new('nbInscriptionsMax'),
            Field::new('infosSortie'),
            AssociationField::new('site'),
            AssociationField::new('etat'),
            AssociationField::new('lieu'),
        ];

        return $fields;
    }
}
