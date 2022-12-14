<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Remplir ce champ'
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Veuillez entrer un titre valide'
                    ]),
                    ],
            ])
            ->add('description', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Remplir ce champ'
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Veuillez entrer une description valide'
                    ]),
                    ],
            ])
            ->add('price', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Remplir ce champ'
                    ]),
                    new Length([
                        'min' => 0,
                        'minMessage' => 'Veuillez entrer un prix valide, supérieur à 0€',
                        'max' => 6,
                        'maxMessage' => 'Veuillez entrer un prix valide, inférieur à 999 999€'
                    ]),
                    ],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Envoyer']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
