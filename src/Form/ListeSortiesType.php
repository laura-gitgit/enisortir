<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListeSortiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('nom')
//            ->add('dateHeureDebut')
//            ->add('duree')
//            ->add('dateLimiteInscription')
//            ->add('nbInscriptionsMax')
//            ->add('infosSortie')
//            ->add('participants')
//            ->add('organisteur')
            ->add('site', EntityType::class,
            ["class" => Site::class, "choice_label" => "nom"])
//            ->add('etat')
//            ->add('lieu')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
