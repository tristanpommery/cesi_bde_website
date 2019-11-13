<?php

namespace App\Form;

use App\Entity\Event;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('date', DateType::class)
            ->add('description', TextareaType::class)
            ->add('image', FileType::class, [
                'required'=>false,
                'constraints'=>[
                    new File([
                        'mimeTypes'=>[
                            'image/png',
                            'image/jpg'
                        ],
                        'mimeTypesMessage'=>'Please upload a valid Image Document (PNG / JPG)'
                    ])
                ]
            ])
            ->add('price', IntegerType::class)
            ->add('duration', DateIntervalType::class)
            ->add('period', TextType::class)
            ->add('fakeUsers')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
