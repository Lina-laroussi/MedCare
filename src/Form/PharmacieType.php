<?php

namespace App\Form;

use App\Entity\Pharmacie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;



class PharmacieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('nom')
            ->add('adresse' ,TextType::class  )
            ->add('num_tel')
            ->add('email', EmailType::class,
            [
            'required' => true
            ])
            ->add('matricule')
            ->add('gouvernorat',ChoiceType::class, [
                'choices'  => [
             'Ariana' =>"Ariana",
           'Béja'=>"Béja",
             'Ben Arous'=>"Ben Arous",
              'Bizerte'=>"Bizerte",
             'Gabès'=>"Gabès",
              'Gafsa'=>"Gafsa",
              'Jendouba'=>"Jendouba",
             'Kairouan'=>"Kairouan",
             'Kasserine'=>"Kasserine",
             'Kébili'=>"Kébili",
             'Kef'=>"Kef",
             'Mahdia'=>"Mahdia",
             'Manouba'=>"Manouba",
            'Médenine'=>"Médenine",
             'Monastir'=>"Monastir",
           'Nabeul'=>"Nabeul",
             'Sfax'=>"Sfax",
            'Sidi Bouzid'=>"Sidi Bouzid",
           'Siliana'=>"Siliana",
           'Sousse'=>"Sousse",
            'Tataouine'=>"Tataouine",
             'Tozeur'=>"Tozeur",
           'Tunis'=>"Tunis",
           'Zaghouan'=>"Zaghouan"   ]]) 

            ->add('horaire')
            ->add('etat',ChoiceType::class, [
                'choices'  => [
                    'Ouvert' => "Ouvert",
                    'Ferme' =>"Ferme"
                ]]) 
             ->add('description',TextareaType::class, 
                [
                    'attr' => ['class' => 'form-control'],
                    'required' => true
                ]) 

            ->add('services',TextareaType::class,
             [
                'attr' => ['class' => 'form-control'],
                'required' => true
            ])      
          ->add('pharmacien')






        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pharmacie::class,
        ]);
    }
}
