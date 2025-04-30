<?php

namespace App\Form;

use App\Entity\Cinema;
use App\Entity\Qualite;
use App\Entity\Salle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbPlace', IntegerType::class, [
                'label' => 'Nombre de Places',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('numSalle', IntegerType::class, [
                'label' => 'Numéro de Salle',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('nbPlacePmr', IntegerType::class, [
                'label' => 'Nombre de Places PMR',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('cinema', EntityType::class, [
                'label' => 'Cinéma',
                'class' => Cinema::class,
                'choice_label' => 'ville',
                'attr' => ['class' => 'form-select'],
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('qualite', EntityType::class, [
                'label' => 'Qualité',
                'class' => Qualite::class,
                'choice_label' => 'libelle',
                'attr' => ['class' => 'form-select'],
                'label_attr' => ['class' => 'form-label'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Salle::class,
        ]);
    }
}