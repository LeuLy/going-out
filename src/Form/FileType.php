<?php

namespace App\Form;

use App\Entity\File;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('path')
            ->add('name')
            ->add('mimeType')
            ->add('size')
            ->add('publicPath')
            ->add('user')*/
            ->add(
                'file',
                \Symfony\Component\Form\Extension\Core\Type\FileType::class,
                [
                    'label' => 'Photo',
                    'attr' => ['class' => 'form-control']
                    ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => File::class,
        ]);
    }
}
