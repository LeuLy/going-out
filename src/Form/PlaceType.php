<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Place;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
                    'label' => 'Nom du lieu',
                ]
                )
            ->add('address',
                TextType::class,
                [
                    'label' => 'Adresse',
                ])
/*            ->add('latitude')
            ->add('longitude')*/
            ->add (
                    'city',
                    TextType::class,
                    [
                            'label' => 'Ville',
                            'mapped' => false,
                    ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
