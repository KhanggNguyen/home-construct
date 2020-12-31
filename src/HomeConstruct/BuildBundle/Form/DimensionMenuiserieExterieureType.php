<?php

namespace HomeConstruct\BuildBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DimensionMenuiserieExterieureType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('longueur', NumberType::class, [
                'label' => 'Longueur (en m) *',
                'required' => true,
                'attr' => array(
                    'maxLength' => '64',
                    'step'=>"0.01",
                    'min'=> '0.01'
                )
            ])
            ->add('largeur', NumberType::class, [
                'label' => 'Largeur (en m) *',
                'required' => true,
                'attr' => array(
                    'maxLength' => '64',
                    'step'=>"0.01",
                    'min'=> '0.01'
                )
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\BuildBundle\Entity\DimensionMenuiserieExterieure'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_dimensionmenuiserieexterieure';
    }


}
