<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control form-control-sm']
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control form-control-sm']
            ])
            ->add('phone', TextType::class, [
                'attr' => ['class' => 'form-control form-control-sm']
            ])
            ->add('address', TextType::class, [
                'attr' => ['class' => 'form-control form-control-sm']
            ])
            ->add('zipcode', TextType::class, [
                'attr' => ['class' => 'form-control form-control-sm']
            ])
            ->add('city', TextType::class, [
                'attr' => ['class' => 'form-control form-control-sm']
            ])
            ->add('discount', NumberType::class, [
                'attr' => ['class' => 'form-control form-control-sm']
            ])
            ->add('description', TextType::class, [
                'attr' => ['class' => 'form-control form-control-sm']
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-secondary',
                    'value' => 'Save']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
