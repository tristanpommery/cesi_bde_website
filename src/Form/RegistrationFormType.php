<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Category;
use App\Entity\Promotion;
use App\Entity\User;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class)
            ->add('firstName', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('genre', ChoiceType::class, [
                'placeholder'=>'Choisis ton sexe',
                'empty_data'=>null,
                'choices' => [
                    'Homme'=>'H',
                    'Femme'=>'F',

                ]
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'data_class'=>null,
                'constraints'=>[
                    new File([
                        'mimeTypes'=>[
                            'image/png',
                            'image/jpeg'
                        ],
                        'mimeTypesMessage'=>'Please upload a valid image format'
                    ])
                ]
            ])
            ->add('promotion', EntityType::class , [
                'class'=>Promotion::class,
                'choice_label'=>'name',
            ])
            ->add('campus', EntityType::class, [
                'class'=>Campus::class,
                'choice_label'=>'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
