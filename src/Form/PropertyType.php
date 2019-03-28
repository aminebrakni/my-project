<?php

namespace App\Form;

use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',null,[
                'label' => 'Titre'
            ])
            ->add('description',null,[
                'label' => 'Description'
            ])
            ->add('surface',null,[
                'label' => 'Surface'
            ])
            ->add('rooms',null,[
                'label' => 'Pièce'
            ])
            ->add('bedrooms',null,[
                'label' => 'Chambre'
            ])
            ->add('floor',null,[
                'label' => 'Étage'
            ])
            ->add('price',null,[
                'label' => 'Prix'
            ])
            ->add('heat',ChoiceType::class,[
                'label' => 'Chauffage',
                'choices' => $this->getChoices()
            ])
            ->add('city',null,[
                'label' => 'Ville'
            ])
            ->add('adress',null,[
                'label' => 'Adresse'
            ])
            ->add('postal_code',null,[
                'label' => 'Code Postal'
            ])
            ->add('sold',null,[
                'label' => 'Vendu'
            ])
            // ->add('created_at')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }

    private function getChoices(){
        $choices = Property::HEAT;
        $output = [];
        foreach ($choices as $k => $v) {
            $output[$v] = $k;
            # code...
        }
        return $output;
    }
}