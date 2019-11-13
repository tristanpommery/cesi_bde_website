<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Gallery;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class GalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class, [
                'required'=>false,
                'constraints' =>[
                    new File([
                        'mimeTypes'=>[
                            'image/png',
                            'image/jpg'
                        ],
                        'mimeTypesMessage'=>'Please upload a valid Image Document (PNG / JGP)'
                    ])
                ]
            ])
            ->add('event', EntityType::class,[
                'class'=>Event::class,
                'choice_label'=>'name'
            ])
            ->add('fakeUser')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gallery::class,
        ]);
    }
}
