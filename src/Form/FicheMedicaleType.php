<?php

namespace App\Form;

use App\Entity\FicheMedicale;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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

            /*->add('CIN', EntityType::class,[
                'class' => User::class,
                'choice_label' => 'CIN',
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('num_securite_sociale', EntityType::class,[
                'class' => User::class,
                'choice_label' => 'num_securite_sociale',
                'attr' => ['class' => 'form-control form-group']
            ])
            */
            ->add('description', TextType::class, [
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('allergies', TextType::class, [
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('pathologie', TextType::class, [
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
