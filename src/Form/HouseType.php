<?php

namespace App\Form;

use App\Entity\House;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class HouseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('latitude')
            ->add('longitude')
            ->add('address')
            ->add('capacity')
            ->add('num_rooms')
            ->add('num_bedrooms')
            ->add('num_bathrooms')
            ->add('daily_price')
            ->add('description')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('actif', CheckboxType::class, [
                'label'    => 'Actif ', // Personnalisez le label comme vous le souhaitez
                'required' => false, // Rend le champ non requis, l'utilisateur peut ne pas le cocher
                'attr' => ['class' => 'custom-class'] // Vous pouvez ajouter des attributs HTML ici si nécessaire
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => House::class,
        ]);
    }
}
