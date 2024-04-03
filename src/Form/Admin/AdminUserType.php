<?php

namespace App\Form\Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\File;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles = [
            'Super Admin'   => 'ROLE_SUPER_ADMIN',
            'Admin'         => 'ROLE_ADMIN',
            'Editor'        => 'ROLE_EDITOR',
            'User'          => 'ROLE_USER'
        ];

        $builder
            ->add('email', TextType::class, [
                'required' => true
            ])
            ->add('username', TextType::class, [
                'required' => true
            ])
            ->add('isVerified', CheckboxType::class, [
                'required' => true
            ])
            ->add('private', CheckboxType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Roles',
                'choices' => $roles,
                'required' => true,
                'multiple' => true,
                'expanded' => true
            ])
            ->add('avatar', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            // ici on déclare les conditions pour faire aparaitre les champs selon le mode (edition ou creation)
            //
        // if ($options['mode'] == 'User::CREATION_MODE' || $options['mode'] == User:: PASSWORD_RESET_MODE) {
            //    $builder->add('password', PasswordType::class, [
            //        'required' => true,
            //       'hash_property_path' => 'password',
            //        'mapped' => false,
            //]); 
            // }
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            // 'mode'=> 'edition', // ici on déclare les options pour le mode ('edition' ou 'creation') et à partir d'ici, on peut utliser les optionss ci-dessus et AdminUserController.php
        ]);
    }
}
