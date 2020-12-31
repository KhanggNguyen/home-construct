<?php

namespace HomeConstruct\BuildBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VentilationPieceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', EntityType::class, [
            'class'        => 'HomeConstruct\BuildBundle\Entity\TypeVentilation',
            'choice_label' => 'nom',
            'multiple'     => false,
            'label' => 'Type : *',
            'empty_data'=>null,
            'required' => false,
            'attr' => array(
                'onchange'=>"updateInputRequiredVentilation()"
            )
        ])
            ->add('quantite', IntegerType::class, [
                'label' => 'Quantité : *',
                'required' => false,
                'attr' => array(
                    'maxLength' => '64',
                    'step'=>"1",
                    'min'=> '1',
                    'max'=>'100',
                    'oninput'=>"updateInputRequiredVentilation()"
                ),
            ])
            ->remove('prix')
            ->remove('secondOeuvre');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\BuildBundle\Entity\Ventilation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_ventilationpiece';
    }


}
