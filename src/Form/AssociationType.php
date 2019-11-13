<?php

namespace App\Form;

use App\Entity\Association;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AssociationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('Description', TextareaType::class)
            ->add('image', FileType::class, [
                'required'=>false,
                'constraints'=>[
                    new File([
                        'mimeTypes'=>[
                            'image/png',
                            'image /jpg'
                        ],
                        'mimeTypesMessaqe'=>'Please upload a valid Image format (PNG / JPG)'
                    ])
                ]
            ])
            ->add('fakeUsers')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Association::class,
        ]);
    }
}
