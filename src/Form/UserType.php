<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'label' => 'Pseudo',
                    'attr' => ['class' => 'form-control'],
                ]
            )
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Nom',
                    'attr' => ['class' => 'form-control'],
                ]
            )
            ->add(
                'firstname',
                TextType::class,
                [
                    'label' => 'Prénom',
                    'attr' => ['class' => 'form-control'],
                ]
            )
            ->add('phone',
                TextType::class,
                [
                    'label' => 'Téléphone',
                    'attr' => ['class' => 'form-control'],
                    'constraints' => [
                        new Length([
                            'min' => 10,
                            'max' => 10,
                        ])
                    ]
                ]
            )
            ->add('email',
                    TextType::class,
                    [
                        'label' => 'Email',
                        'attr' => ['class' => 'form-control'],
                    ]
            )
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'form-control']],
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
