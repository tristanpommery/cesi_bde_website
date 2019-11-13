<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Entity\Association;
use Symfony\Component\Form\Exception;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProductType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('price', NumberType::class)
            ->add('stock', IntegerType::class)
            ->add('image', FileType::class, [
                'required'=>false,
                'data_class' => null,
                'constraints'=> [
                    new File([
                        'maxSize'=>'50M',
                        'mimeTypes'=>[
                            'image/png',
                            'image/jpg'
                        ],
                        'mimeTypesMessage'=>'Please upload a valid Image Document (PNG / JPG)',
                    ]),
                ],
            ])
            ->add('category', EntityType::class, [
                'class'=> Category::class,
                'choice_label'=>'name',
                'placeholder'=>'Choisissez la catégorie',
                'empty_data'=>null
            ])
            ->add('association', EntityType::class, [
                'required' => false,
                'class'=>Association::class,
                'choice_label'=>'name',
                'placeholder'=>'Choisissez l\'association',
                'empty_data'=>null,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }

    /**
     * Maps the view data of a compound form to its children.
     *
     * The method is responsible for calling {@link FormInterface::setData()}
     * on the children of compound forms, defining their underlying model data.
     *
     * @param mixed $viewData View data of the compound form being initialized
     * @param FormInterface[]|\Traversable $forms A list of {@link FormInterface} instances
     *
     * @throws Exception\UnexpectedTypeException if the type of the data parameter is not supported
     */
    public function mapDataToForms($viewData, $forms)
    {
        // TODO: Implement mapDataToForms() method.
    }

    /**
     * Maps the model data of a list of children forms into the view data of their parent.
     *
     * This is the internal cascade call of FormInterface::submit for compound forms, since they
     * cannot be bound to any input nor the request as scalar, but their children may:
     *
     *     $compoundForm->submit($arrayOfChildrenViewData)
     *     // inside:
     *     $childForm->submit($childViewData);
     *     // for each entry, do the same and/or reverse transform
     *     $this->dataMapper->mapFormsToData($compoundForm, $compoundInitialViewData)
     *     // then reverse transform
     *
     * When a simple form is submitted the following is happening:
     *
     *     $simpleForm->submit($submittedViewData)
     *     // inside:
     *     $this->viewData = $submittedViewData
     *     // then reverse transform
     *
     * The model data can be an array or an object, so this second argument is always passed
     * by reference.
     *
     * @param FormInterface[]|\Traversable $forms A list of {@link FormInterface} instances
     * @param mixed $viewData The compound form's view data that get mapped
     *                                               its children model data
     *
     * @throws Exception\UnexpectedTypeException if the type of the data parameter is not supported
     */
    public function mapFormsToData($forms, &$viewData)
    {
        // TODO: Implement mapFormsToData() method.
    }
}
