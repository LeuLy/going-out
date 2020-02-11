<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Place;
use App\Entity\Site;
use App\Entity\Status;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
//use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'label',
                TextType::class,
                [
                    'label' => 'Nom de la sortie',
                ]
            )
            ->add(
                'dateStart',
                DateTimeType::class,
//                datetime
                [
                    'label' => 'Date de début',
                    'time_widget' => 'single_text',
                    'date_widget' => 'single_text',
                ]
            )
            ->add(
                'duration',
                TimeType::class,
                [
                    'label' => 'Durée de la sortie',
                    'widget' => 'single_text',
                ]
            )
            ->add(
                'dateInscriptionEnd',
                DateTimeType::class,
                [
                    'label' => 'Date de fin d \'inscription',
                    'time_widget' => 'single_text',
                    'date_widget' => 'single_text',
                ]
            )
            ->add(
                'maxMembers',
                NumberType::class,
                [
                    'label' => 'Nombre maximum de participants',
                ]
            )
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'description',
                ]
            )
            ->add(
                'site',
                EntityType::class,
                [
                    'label' => 'Site',
                    'class' => Site::class,
                    'choice_label' => 'label',
                ]
            )
            ->add(
                'status',
                EntityType::class,
                [
                    'label' => 'Status',
                    'class' => Status::class,
                    'choice_label' => 'label',
                ]
            )
            ->add (
                    'place',
                    EntityType::class,
                    [
                            'label' => 'Lieu',
                            'class' => Place::class,
                            'choice_label' => 'label',
                    ]
            )
            ->add (
                    'placeForm',
                    PlaceType::class,
                    [
                            'mapped' => false,
                    ]
            )
            ->add(
                    'save',
                    SubmitType::class,
                    [
                            'label' => 'Envoyer',
                    ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Event::class,
            ]
        );
    }
}
