<?php

namespace App\Form\User;

use App\Model\User\UserResetPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder
            ->add('password', PasswordType::class, [
                'required' => true,
                'hash_property_path' => 'password',
                'mapped' => false,
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'New Password'],
                'second_options' => ['label' => 'Repeat New Password'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserResetPassword::class,
        ]);
    }
}
