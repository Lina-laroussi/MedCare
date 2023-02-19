<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotNullValidator;

class EditFormUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',\Symfony\Component\Form\Extension\Core\Type\TextType::class,['disabled' => true])
            ->add('prenom',TextType::class,['disabled' => true])
            ->add('date_de_naissance',DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                ])

            ->add('email',\Symfony\Component\Form\Extension\Core\Type\EmailType::class,['disabled' => true])

            ->add('num_tel',)
            ->add('sexe',ChoiceType::class, [
                'choices'  => [
                    'Femme' => "femme",
                    'Homme' =>"Homme"
                ]])
            ->add('adresse',\Symfony\Component\Form\Extension\Core\Type\TextType::class )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
