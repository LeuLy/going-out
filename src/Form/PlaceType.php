<?php

namespace App\Form;

use App\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label',
                TextType::class,
                [
                    'label' => 'Nom',
                ]
                )
            ->add('address',
                TextType::class,
                [
                    'label' => 'Adresse',
                ])
            ->add('latitude')
            ->add('longitude')
//            ->add(
//                    'save',
//                    ButtonType::class,
//                    [
//                            'label' => 'Enregistrer',
//                    ]
//            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
