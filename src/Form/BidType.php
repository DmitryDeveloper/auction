<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class BidType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 3, 'max' => 255]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('startPrice', MoneyType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => 'numeric']),
                ],
            ])
            ->add('buyImmediatelyPrice', MoneyType::class, [
                'constraints' => [
                    new Assert\Type(['type' => 'numeric']),
                ],
            ])
            ->add('finishDate', DateTimeType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\DateTime(),
                ],
            ])
            ->add('productIds', CollectionType::class, [
                'entry_type' => IntegerType::class,
                'allow_add' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Count(['min' => 1]),
                ],
            ]);
    }
}
