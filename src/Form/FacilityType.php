<?php
 
namespace App\Form;
 
use App\Entity\Facility;
use App\Entity\House;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
 
class FacilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       
        $builder
            ->add('name')
            ->add('house', EntityType::class, [
                'class' => House::class,
                'choice_label' => 'id',
                'mapped' => true,
                // 'disabled' => true,
                'data' => $options['house_id'], // Utilisez la valeur passée depuis le contrôleur
            ]);
    }
 
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facility::class,
            'house_id' => null,
        ]);
    }
}
 
