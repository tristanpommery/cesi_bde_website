<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Period;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;

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
                'dataclass'=> null,
                'constraints'=>[
                    new File([
                        'maxSize'=>'50M',
                        'mimeTypes'=>[
                            'image/png',
                            'image/jpg'
                        ],
                        'mimeTypesMessage'=>'Please upload a valid Image Document (heidiPNG / JPG)'
                    ])
                ]
            ])
            ->add('price', NumberType::class)
            ->add('duration', TextType::class)
            ->add('localization', TextType::class)
            ->add('period', EntityType::class, [
                'required' => false,
                'class'=>Period::class,
                'choice_label'=>'time',
                'placeholder'=>'--Choisissez la période--',
                'empty_data'=>null,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
