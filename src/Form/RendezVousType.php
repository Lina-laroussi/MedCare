<?php

namespace App\Form;

use App\Entity\RendezVous;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date',DateType::class, [
               
                'label' => 'Date Debut',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => 'form-control form-group'],
            ])
            ->add('heure_debut',TimeType::class, [
                'label' => 'Heure Debut',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control form-group'],
            ])
            ->add('heure_fin',TimeType::class, [
                'label' => 'Heure Debut',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control form-group'],
            ])
            ->add('symptomes',TextareaType::class, [
                'attr' => ['class' => 'form-control'],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,

        ]);
    }
}