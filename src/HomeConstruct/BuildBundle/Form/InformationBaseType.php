<?php

namespace HomeConstruct\BuildBundle\Form;

use Doctrine\DBAL\Types\FloatType;
use Leafo\ScssPhp\Node\Number;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InformationBaseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $valueComble = $options['valueComble'];
        $valueSousSol = $options['valueSousSol'];

        $builder
            ->add('surfaceTotale', NumberType::class, [
                'label' => 'Surface totale (en m²) : *',
                'required' => true,
                'attr' => array(
                    'maxLength' => '64',
                    'step'=>"0.01",
                    'min'=> '0'
                )
            ])
            ->add('nbPieces', IntegerType::class, [
                'label' => 'Nombre de pièces : *',
                'required' => true,
                'attr' => array(
                    'maxLength' => '64',
                    'step'=>"1",
                    'min'=> '1'
                )
            ])
            ->add('comble', ChoiceType::class, [
                'label' => 'Présence de comble : *',
                'required' => true,
                'choices'  => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'data' => $valueComble,
                'expanded' => true,
                'multiple' => false
            ])
            ->add('sousSol', ChoiceType::class, [
                'label' => 'Présence d\'un sous sol : *',
                'required' => true,
                'choices'  => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'data' => $valueSousSol,
                'expanded' => true,
                'multiple' => false
            ])
            ->add('assainissement', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\Assainissement',
                'choice_label' => 'nom',
                'multiple'     => false,
                'label' => 'Assainissement : *',
                'required' => true,
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\BuildBundle\Entity\InformationBase'
        ))
        ->setRequired('valueComble')->setRequired('valueSousSol');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_informationbase';
    }


}
