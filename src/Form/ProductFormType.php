<?php

namespace App\Form;

use App\Entity\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class, [
                'label' => 'Product Description',
                'attr' => [
                    'placeholder' => 'Enter Product Description'
                ]
            ])
            ->add('imagepath', FileType::class, array(
                'label' => 'Product Image (png/jpg file)',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PNG or JPG document',
                    ])
                ],
            ))
            ->add('price', NumberType::class, [
                'label' => 'Product Price',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter Product Price'
                ]
            ])
            ->add('stock', NumberType::class, [
                'label' => 'Initial Product Stock',
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter Product Stock'
                ]
            ])
            ->add('minage', NumberType::class, [
                'label' => 'Product Minimum Age',
                'attr' => [
                    'placeholder' => 'Enter Product Minimum Age'
                ]
            ])
            ->add('launchyear', NumberType::class, [
                'label' => 'Product Launch Year',
                'attr' => [
                    'placeholder' => 'Enter Product Launch Year'
                ]
            ])
            ->add('version', NumberType::class, [
                'label' => 'Product Version',
                'attr' => [
                    'placeholder' => 'Enter Product Version'
                ]
            ])
            ->add('author', TextType::class, [
                'label' => 'Product Author',
                'attr' => [
                    'placeholder' => 'Enter Product Author'
                ]
            ])
            ->add('type', TextType::class, [
                'label' => 'Product Type',
                'attr' => [
                    'placeholder' => 'Enter Product Type'
                ]
            ])


        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'csrf_field_name' => '_token',
        ]);
    }
}
