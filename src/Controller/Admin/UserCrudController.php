<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\{Action, Actions, Crud, KeyValueStore};
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\{AssociationField, Field, TextField};
use Symfony\Component\Form\Extension\Core\Type\{PasswordType, RepeatedType};
use Symfony\Component\Form\{FormBuilderInterface, FormEvent, FormEvents};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        public UserPasswordHasherInterface $userPasswordHasher
    ) {}

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::DETAIL)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            Field::new('email'),
            Field::new('nom'),
            Field::new('prenom'),
            Field::new('pseudo'),
            Field::new('telephone'),
            Field::new('administrateur'),
            Field::new('actif'),
            AssociationField::new('site'),
        ];

        if ($pageName === Crud::PAGE_NEW) {
            $password = TextField::new('password')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Confirmation du mot de passe.'],
                    'required' => true,
                    'mapped' => false,
                ])
                ->onlyOnForms();
            $fields[] = $password;
        } elseif ($pageName === Crud::PAGE_EDIT) {
            $password = TextField::new('password')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'Changer le mot de passe de l\'utilisateur ? (Facultatif)'],
                    'second_options' => ['label' => 'Confirmation de la modification du mot de passe si changement.'],
                    'required' => false,
                    'mapped' => false,
                ])
                ->onlyOnForms();
            $fields[] = $password;
        }

        return $fields;
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        if ($context->getCrud()->getCurrentPage() === Crud::PAGE_NEW) {
            return $this->addPasswordEventListener($formBuilder, $entityDto->getInstance());
        }
        return $formBuilder;
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        if ($context->getCrud()->getCurrentPage() === Crud::PAGE_EDIT) {
            return $this->addPasswordEventListener($formBuilder, $entityDto->getInstance());
        }
        return $formBuilder;
    }

    private function addPasswordEventListener(FormBuilderInterface $formBuilder, User $user): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use ($user) {
            $form = $event->getForm();
            if (!$form->isValid()) {
                return;
            }
            $password = $form->get('password')->getData();
            if ($password === null) {
                return;
            }

            $hash = $this->userPasswordHasher->hashPassword($user, $password);
            $form->getData()->setPassword($hash);
        });
    }

}