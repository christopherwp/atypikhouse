<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('username')
            ->add('agreeTerms', CheckboxType::class, [
                                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit être au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            // Admin : Gabriela ->
            //->add('roles', ChoiceType::class, [
            //   'mapped' => false, // Indique que ce champ n'est pas directement lié à une propriété de l'entité User
            //    'choices' => [
            //        'User' => 'ROLE_USER',
            //        'Admin' => 'ROLE_ADMIN',
            //        'Proprio'=> 'ROLE_PROPRIO'
            //    ],
            //    'expanded' => true, // Les choix apparaissent comme des boutons radio
            //    'multiple' => true, // Permet la sélection de plusieurs rôles

                
            //]) // Admin : Gabriela <-
        ;
        if ($options['afficher_champs_speciaux']) {
            $builder
                ->add('adresse')
                ->add('telephone')
                ->add('numCarteIdentite');
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'afficher_champs_speciaux' => false,
        ]);
    }
}
