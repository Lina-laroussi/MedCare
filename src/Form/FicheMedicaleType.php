<?php

namespace App\Form;

use App\Entity\FicheMedicale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FicheMedicaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description',TextType::class, [
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('allergies', TextType::class, [
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('pathologie', TextType::class, [
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('date_de_creation', DateType::class,[
                'widget' => 'single_text',
                'html5' => false,
                'data' => new \DateTime(),
                'format' => 'dd/MM/yyyy',
                'attr' => ['class' => 'form-control form-group']
            ])

            ->add('date_de_modification', DateTimeType::class,[
                'widget' => 'single_text',
                'html5' => false,
                'data' => new \DateTime(),
                'format' => 'dd/MM/yyyy H:m:s',
                'attr' => ['class' => 'form-control form-group']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FicheMedicale::class,
        ]);
    }
}
