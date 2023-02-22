<?php

namespace App\Form;

use App\Entity\Consultation;
use App\Entity\Ordonnance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;



class OrdonnanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class)
            ->add('code_ordonnance', TextType::class, [
                'disabled' => true,
                'data' => $options['pre_generated_code'], // pass the pre-generated code as an option
            ])
            ->add('medicaments', TextType::class)
            ->add('dosage', TextType::class)
            ->add('nombre_jours', IntegerType::class)
            ->add('date_de_creation', DateType::class,[
                'widget' => 'single_text',
                'html5' => false,
                'data' => new \DateTime(),
                'format' => 'dd/MM/yyyy'
            ])
            ->add('date_de_modification', DateTimeType::class,[
                'widget' => 'single_text',
                'html5' => false,
                'data' => new \DateTime(),
                'format' => 'dd/MM/yyyy H:m:s'
            ])
            ->add('consultation', EntityType::class,[
                'class' => Consultation::class,
                'choice_label' => 'id'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ordonnance::class,
            'pre_generated_code' => null, // set the default value of the option to null
        ]);
    }
}
