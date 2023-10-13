<?php

namespace App\Form\Type;

use App\Entity\Projects;
use App\Entity\Store;
use App\Services\Helpers\FormHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StoreType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                FormHelper::REQUIRED => true,
                FormHelper::LABEL => "Store Name",
                FormHelper::ATTR => [
                    FormHelper::CLS => 'form-control '
                ],
                'required' => true,

            ])
            ->add('type', ChoiceType::class, [
                FormHelper::REQUIRED => true,
                FormHelper::LABEL => "Type",
                FormHelper::ATTR => [
                    FormHelper::CLS => 'form-control my-2 select2'
                ],
                'choices'  => [
                    Store::WooCommerce_TYPE => Store::WooCommerce_TYPE
                ]

            ])
            ->add('domain', TextType::class, [
                FormHelper::REQUIRED => true,
                FormHelper::LABEL => "Domain",
                FormHelper::ATTR => [
                    FormHelper::CLS => 'form-control',
                    FormHelper::PLACEHOLDER => "https://example.com",
                ],
                FormHelper::MAPPED => false,

            ])
            ->add('ck', TextType::class, [
                FormHelper::REQUIRED => true,
                FormHelper::LABEL => "Customer Key",
                FormHelper::ATTR => [
                    FormHelper::CLS => 'form-control'
                ],
                FormHelper::MAPPED => false


            ])
            ->add('cs', TextType::class, [
                FormHelper::REQUIRED => true,
                FormHelper::LABEL => "Customer Secret",
                FormHelper::ATTR => [
                    FormHelper::CLS => 'form-control '
                ],
                FormHelper::MAPPED => false


            ])
            ->add('submit', SubmitType::class, [
                FormHelper::LABEL => "Save it",
                FormHelper::ATTR => [
                    FormHelper::CLS => 'btn my-2 mx-auto'
                ]
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
