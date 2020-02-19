<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserModifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',
                    TextType::class,
                    [
                            'label' => 'Pseudo',
                            'attr' => ['class' => 'form-control'],
                    ]
            )
            ->add('name',
                    TextType::class,
                    [
                            'label' => 'Nom',
                            'attr' => ['class' => 'form-control'],
                    ]
            )
            ->add('firstname',
                    TextType::class,
                    [
                            'label' => 'Prénom',
                            'attr' => ['class' => 'form-control'],
                    ]
            )
            ->add('inscriptionYear',
                    IntegerType::class,
                    [
                            'label' => 'Année d\'inscription',
                            'attr' => ['class' => 'form-control'],
                            'constraints' => [
                                    new Length([
                                            'min' => 4,
                                            'max' => 4,
                                    ])
                            ]
                    ]
            )
            ->add('phone',
                    TextType::class,
                    [
                            'label' => 'Téléphone',
                            'required' => false,
                            'attr' => ['class' => 'form-control'],
                            'constraints' => [
                                    new Length([
                                            'min' => 10,
                                            'max' => 10,
                                    ])
                            ]
                    ]
            )
            ->add('showPhone',
                    CheckboxType::class,
                    [
                            'label' => 'Montrer le numéro de téléphone',
                            'required' => false,
                    ]
            )
            ->add('email',
                    TextType::class,
                    [
                            'label' => 'Email',
                            'attr' => ['class' => 'form-control'],
                    ]
            )
//            ->add('active')
//            ->add('erased')
            ->add('site',
                    EntityType::class,
                    [
                            'label' => 'Site',
                            'attr' => ['class' => 'form-control'],
                            'class' => Site::class,
                            'choice_label' => 'label',
                    ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
