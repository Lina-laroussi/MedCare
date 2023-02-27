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
             'Ariana' =>"v1",
           'Béja'=>"v2",
             'Ben Arous'=>"v3",
              'Bizerte'=>"v4",
             'Gabès'=>"v5",
              'Gafsa'=>"v6",
              'Jendouba'=>"v7",
             'Kairouan'=>"v8",
             'Kasserine'=>"v9",
             'Kébili'=>"v10",
             'Kef'=>"v11",
             'Mahdia'=>"v12",
             'Manouba'=>"v13",
            'Médenine'=>"v14",
             'Monastir'=>"v15",
           'Nabeul'=>"v16",
             'Sfax'=>"v17",
            'Sidi Bouzid'=>"v18",
           'Siliana'=>"v19",
           'Sousse'=>"v20",
            'Tataouine'=>"v21",
             'Tozeur'=>"v22",
           'Tunis'=>"v23",
           'Zaghouan'=>"v24"   ]]) 

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
