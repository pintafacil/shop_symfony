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

class EditFormType extends AbstractType
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
            ->add('imagepath', TextType::class,
                [
                    'label' => 'Current Product Image Path',
                    'required' => false,
                    'disabled' => true,
                ]
            )
            ->add('newimagepath', FileType::class, array(
                'label' => 'Product Image (png/jpg file)',
                'mapped' => false,
                'required' => false,
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
                'required' => false,
                'attr' => [
                    'placeholder' => 'Enter Product Price'
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
