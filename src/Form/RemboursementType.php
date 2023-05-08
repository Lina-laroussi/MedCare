<?php

namespace App\Form;

use App\Entity\FicheAssurance;
use App\Entity\Remboursement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class RemboursementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant_a_rembourser', TextType::class, [
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('montant_maximale', TextType::class, [
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('taux_remboursement', TextType::class, [
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('date_remboursement', DateType::class, [
                'attr' => ['class' => 'form-control form-group']
            ])
            ->add('etat', ChoiceType::class, [
                'choices'  => [
                    'Non confirmé' => 'Non confirmé',
                    'confirmée' => "confirmée",
                    'attr' => ['class' => 'form-control form-group']
                ],
            ])
            ->add('FicheAssurance', EntityType  ::class, [
                // looks for choices from this entity
                'class' => FicheAssurance::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'id',
            
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Remboursement::class,
        ]);
    }
}
