<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('dateHeureDebut', DateTimeType::class,
                [
                    'html5' => true,
                    'widget' => 'single_text'
                ])
            ->add('duree', IntegerType::class)
            ->add('dateLimiteInscription', DateType::class,
                [
                    'html5' => true,
                    'widget' => 'single_text'
                ])
            ->add('nbInscriptionsMax', IntegerType::class)
            ->add('infosSortie', TextareaType::class)
            ->add('lieu', EntityType::class,
                ["class" =>Lieu::class, 'placeholder' => 'Sélectionnez un lieu', "choice_label" => "nom"])

            ->add('Enregistrer', SubmitType::class, ['attr' => ['value' => 'Enregistrer']])
            ->add('Publier', SubmitType::class, ['attr' => ['value' => 'Publier']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
