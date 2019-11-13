<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class)
            ->add('createdAt', DateType::class)
            ->add('event', EntityType::class, [
                'class'=>Event::class,
                'choice_label'=>'name'
            ])
            ->add('product', EntityType::class, [
                'class'=>Event::class,
                'choice_label'=>'name'
            ])
            ->add('fakeUser')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
