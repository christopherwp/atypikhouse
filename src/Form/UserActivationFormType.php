<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserActivationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) :void
    {
        $builder
            ->add('isActive', CheckboxType::class, [
                'label'    => 'Active',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) :void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
