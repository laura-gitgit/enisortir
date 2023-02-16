<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifierSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut', DateTimeType::class,
                [
                'html5'=>true,
                 'widget'=> 'single_text'
            ])
            ->add('duree')
            ->add('dateLimiteInscription', DateTimeType::class,
                [
                    'html5'=>true,
                    'widget'=> 'single_text'
                ])
            ->add('nbInscriptionsMax')
            ->add('infosSortie')




            ->add('lieu',EntityType::class,
            ["class" => Lieu::class, "choice_label"=>"nom"])

            ->add('Enregistrer', SubmitType::class, ['attr' => ['value' => 'Enregistrer']])
            ->add('Publier', SubmitType::class, ['attr' => ['value' => 'Publier']])
            ->add('Annuler', SubmitType::class, ['attr' => ['value' => 'Annuler']])
            ->add('Supprimer', SubmitType::class,['attr' => ['value' => 'Supprimer']])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
