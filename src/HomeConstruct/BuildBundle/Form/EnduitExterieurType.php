<?php

namespace HomeConstruct\BuildBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EnduitExterieurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('surfaceMur',NumberType::class,[
                    'label' => 'Surface du mur : *',
                    'required' => true,
                    'attr' => array(
                        'maxLength' => 10,
                        'step' => '0.01',
                        'min' => 0
                    )
                ])
                ->add('typeEnduit', EntityType::class, [
                    'class'        => 'HomeConstruct\BuildBundle\Entity\TypeEnduitExterieur',
                    'choice_label' => 'nom',
                    'multiple'     => false,
                    'placeholder' => '---Selectionner une valeur---',
                    'label' => 'Type de l\'enduit de façade : *',
                    'required' => true
                ])
                ->add('save', SubmitType::class, [
                    'label' => 'Enregistrer',
                    'attr' => ['class' => 'bouton_enregistrer btn btn-xl u-btn-lightblue-v3 g-width-160--md g-font-size-default g-ml-10']
                ]);
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\BuildBundle\Entity\EnduitExterieur'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_enduitexterieur';
    }


}
