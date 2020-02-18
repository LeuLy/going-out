<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add(
//                    'username',
//                    TextType::class,
//                    [
//                            'label' => 'Pseudo',
//                    ]
//            )
            ->add(
                    'name',
                    TextType::class,
                    [
                            'label' => 'Nom',
                    ]
            )
            ->add(
                    'firstname',
                    TextType::class,
                    [
                            'label' => 'Prénom',
                    ]
            )
            ->add(
                    'inscriptionYear',
                    IntegerType::class,
                    [
                            'label' => 'Année d\'inscription',
                            'constraints' => [
                                    new Length([
                                            'min' => 4,
                                            'max' => 4,
                                    ])
                            ]
                    ]
            )
//            ->add('phone',
//                    TextType::class,
//                    [
//                            'label' => 'Téléphone',
//                            'required' => false,
//                            'constraints' => [
//                                new Length([
//                                        'min' => 10,
//                                        'max' => 10,
//                                ])
//                            ]
//                    ]
//            )
            ->add(
                'site',
                EntityType::class,
                [
                    'label' => 'Site',
                    'class' => Site::class,
                    'choice_label' => 'label',
                ]
            )
//            ->add('email',
//                    TextType::class,
//                    [
//                            'label' => 'Email'
//                    ]
//            )
            ->add(
                    'agreeTerms',
                    CheckboxType::class,
                    [
                            'label' => 'Conditions d\'utilisation',
                            'mapped' => false,
                            'constraints' => [
                                    new IsTrue(
                                            [
                                                    'message' => 'Vous devez acceptez les Conditions d\'utilisation',
                                            ]
                                    ),
                            ],
                    ]
            )
//            ->add('plainPassword', PasswordType::class, [
//                // instead of being set onto the object directly,
//                // this is read and encoded in the controller
//                'mapped' => false,
//                'constraints' => [
//                    new NotBlank([
//                        'message' => 'Please enter a password',
//                    ]),
//                    new Length([
//                        'min' => 6,
//                        'minMessage' => 'Your password should be at least {{ limit }} characters',
//                        // max length allowed by Symfony for security reasons
//                        'max' => 4096,
//                    ]),
//                ],
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
