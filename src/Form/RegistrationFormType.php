<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Ajoute un champ pour le prénom
            ->add('first_name')
            // Ajoute un champ pour le nom de famille
            ->add('last_name')
            // Ajoute un champ pour l'e-mail
            ->add('email')
            // Ajoute un champ pour le rôle avec des choix prédéfinis (Artiste et Organisateur)
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Artiste' => 'ROLE_PRO',
                    'Organisateur' => 'ROLE_ORGA',
                ],
                'expanded' => true, // Affiche les choix sous forme de boutons radio
                'multiple' => false, // Permet de sélectionner un seul rôle à la fois
                'mapped' => false, // Indique que ce champ n'est pas lié à une propriété de l'entité
            ])
            // Ajoute un champ pour accepter les termes et conditions (case à cocher)
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false, // Indique que ce champ n'est pas lié à une propriété de l'entité
                'constraints' => [
                    // Contrainte pour vérifier si les termes et conditions ont été acceptés
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])
            // Ajoute un champ pour le mot de passe (type Password)
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false, // Indique que ce champ n'est pas lié à une propriété de l'entité
                'attr' => ['autocomplete' => 'new-password'], // Attribut pour désactiver l'autocomplétion
                'constraints' => [
                    // Contraintes pour la longueur minimale et maximale du mot de passe
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
