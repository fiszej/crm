<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-control-sm'
                ]])
            ->add('comments', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-control-sm'
                ]])
            ->add('description', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-control-sm'
                ]
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-secondary',
                    'value' => 'Save'
                ]
            ])
            ->add('time', ChoiceType::class, [
                'choices' => [
                    '1 h' => 1,
                    '2 h' => 2,
                    '3 h' => 3,
                    '4 h' => 4, 
                    '5 h' => 5,
                    '6 h' => 6,
                    '7 h' => 7,
                    '8 h' => 8, 
                    '9 h' => 9, 
                    '10 h' => 10, 
                    '11 h' => 11, 
                    '12 h' => 12,
               ],
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('deadline', DateTimeType::class, [
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('customerId', EntityType::class, [  
                'class' => Customer::class,
                'choice_label' => 'company',
                'attr' => [
                    'class' => 'form-control form-control-sm'
                ]   
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
