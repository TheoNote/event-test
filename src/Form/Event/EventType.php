<?php

namespace App\Form\Event;

use App\Entity\Category;
use App\Entity\Event;
use App\Enum\EventStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;


class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'label' => 'Title',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('description', TextareaType::class,[
               'label' => 'Descrition' ,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('capacity', IntegerType::class,[
                'label' => 'Capacity',
                'constraints' => [
                    new NotBlank(),
                    new Positive(),
                ]
            ])
            ->add('category', EntityType::class,[
                'label' => 'Category',
                'class' => Category::class,
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('status', EnumType::class,[
                'label' => 'Status',
                'choice_label' => 'name',
                'class' => EventStatus::class,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('startAt', DateTimeType::class,[
                'label' => 'Start Date',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('endAt', DateTimeType::class,[
                'label' => 'End Date',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }

}
