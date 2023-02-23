<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class UserCSVType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fichier', FileType::class, [
                'label' => 'Fichier',
                'mapped' => false,
                'required' => true,
                'attr' => ['accept' => '.csv']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Importer'
            ])
            ->setAttributes(['enctype' => 'multipart/form-data']);
    }
}
