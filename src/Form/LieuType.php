<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Serie;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lieu', EntityType::class,
                ["class" =>Lieu::class, "choice_label" => "nom"])
//            ->add('lieu', EntityType::class,
//                ["class" =>Lieu::class, "choice_label" => "ville.nom"])
            ->add('ville', EntityType::class,
                ["class" =>Ville::class, "choice_label" => "nom"])
////            ->add('ville', VilleFormType::class)
;
    }

    /***
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
