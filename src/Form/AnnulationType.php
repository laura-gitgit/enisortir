<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnulationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, [
        'disabled' => true
    ])
            ->add('dateHeureDebut', DateTimeType::class,
                [
                    'html5'=>true,
                    'widget'=> 'single_text',
                    'disabled' => true
                ])
            ->add('duree', TextType::class,[
                'disabled'=> true,

            ])
            ->add('dateLimiteInscription', DateTimeType::class,
                [
                    'html5'=>true,
                    'widget'=> 'single_text',
                    'disabled' => true
                ])
            ->add('nbInscriptionsMax',IntegerType::class,[
                    'disabled' => true
                ])

            ->add('lieu',EntityType::class,
                ["class" => Lieu::class, "choice_label"=>"nom",
                    'disabled' => true
                    ])

            ->add('infosSortie',TextareaType::class)

            ->add('Enregistrer', SubmitType::class, ['attr' => ['value' => 'Enregistrer']])
            ->add('Annuler', SubmitType::class, ['attr' => ['value' => 'Annuler']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
