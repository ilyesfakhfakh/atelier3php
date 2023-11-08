<?php

namespace App\Form;

use App\Entity\Joueur;
use App\Entity\Vote;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add('joueur')
            ->add('noteVote', ChoiceType::class, [
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3'  => '3',
                    '4' =>'4',
                    '5'=>'5',
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true, // Si vous voulez rendre le champ obligatoire
            ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure ici vos options par défaut si nécessaire
        ]);
    }}