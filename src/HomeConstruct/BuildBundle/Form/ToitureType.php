<?php

namespace HomeConstruct\BuildBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToitureType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $valueExpoVent = $options['valueExpoVent'];
        $valueMainDoeuvre = $options['valueMainDoeuvre'];
        $builder
            ->add('m2', NumberType::class, [
                'label' => 'Nombre de mètre carré : *',
                'required' => true,
                'attr' => [
                    'maxLength' => '64',
                    'step'=>"0.01",
                    'min'=> '0',
                    'oninput'=>"showSurfaceTotale(),addCheckedIcone(),updateEstimationPrice()"
                ]
            ])
            ->add('degrePente', NumberType::class, [
                'label' => 'Degré pente (en °) : *',
                'required' => true,
                'attr' => array(
                    'maxLength' => '64',
                    'step'=>"0.01",
                    'min'=> '0'
                )
            ])
            ->add('expoVent', ChoiceType::class, [
                'label' => 'Exposition au vent : *',
                'required' => true,
                'choices'  => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'data' => $valueExpoVent,
                'expanded' => true,
                'multiple' => false
            ])
            ->add('typeCouverture', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\TypeCouverture',
                'choice_label' => 'nom',
                'multiple'     => false,
                'label' => 'Type de couverture *',
                'required' => true,
                'attr' => [
                    'onChange'=>"showPriceType(),updateEstimationPrice()"
                ]
            ])
            ->add('tarifMainDoeuvre', RangeType::class, [
                'label' => 'Tarif max de la main d\'oeuvre  : *',
                'required' => true,
                'attr' => [
                    'min' => 15,
                    'max' => 100,
                    'value'=>$valueMainDoeuvre,
                    'step'=>1,
                    'class'=>"slider",
                    'onchange'=>"updateEstimationPrice()"
                ]
            ])
            ->remove('charpente');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\BuildBundle\Entity\Toiture'
        ))
            ->setRequired('valueExpoVent')
            ->setRequired('valueMainDoeuvre');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_toiture';
    }


}
