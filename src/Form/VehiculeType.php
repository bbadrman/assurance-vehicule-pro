<?php

namespace App\Form;

use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom...',
                    'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom est requis'])
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom...',
                    'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prénom est requis'])
                ]
            ])
            ->add('raison', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Raison sociale...',
                    'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
                ]
            ])
            ->add('activite', ChoiceType::class, [
                'label' => false,
                'placeholder' => 'Activité...',
                'attr' => [ 
                    'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
                     
                ],
                'choices' => [

                     'Artisans' => 'artisans',
                    'BTP & Construction' => 'BTP et Construction',
                    'Commerciaux & professions libérales' => 'Commerciaux et professions libérales',
                    'Déménagement' =>  'Déménagement',
                    'Santé & Services à la personne' => 'Santé et Services à la personne',
                    'Transport & Logistique' =>  'Transport et Logistique',
                    'Autre' => 'Autre'

                   
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'activité est requise'])
                ]
            ])


            ->add('assurer', ChoiceType::class, [
                'label' => false,
                'placeholder' => 'Véhicule assuré actuellement',
                'choices' => [
                    'Oui' => 'oui',
                    'Non' => 'non'
                ],
                'attr' => [
                    'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez indiquer si le véhicule est assuré'])
                ]
            ])
            ->add('codePostale', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Code Postal...',
                    'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le code postal est requis']),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]{5}$/',
                        'message' => 'Le code postal doit contenir 5 chiffres'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email...',
                    'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'email est requis']),
                    new Assert\Email(['message' => 'L\'email n\'est pas valide'])
                ]
            ])
            ->add('tele', TelType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Téléphone...',
                    'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le téléphone est requis'])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}