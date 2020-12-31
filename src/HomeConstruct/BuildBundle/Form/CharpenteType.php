<?php

namespace HomeConstruct\BuildBundle\Form;

use HomeConstruct\BuildBundle\Repository\TypeCharpenteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharpenteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $valueComble = $options['valueComble'];
        $typeFermette = $options['typeFermette'];
        $valueMainDoeuvre = $options['valueMainDoeuvre'];
        $builder
            ->add('forme', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\FormeCharpente',
                'choice_label' => 'nom',
                'multiple'     => false,
                'label' => 'Forme',
                'required' => true,
            ])
            ->add('tarifMainDoeuvre', RangeType::class, [
                'label' => 'Tarif max de la main d\'oeuvre  : *',
                'required' => true,
                'attr' => [
                    'min' => 15,
                    'max' => 100,
                    'value'=>$valueMainDoeuvre,
                    'step'=>1,
                    //'list'=> "etiquettes_valeur_slider",
                    'class'=>"slider",
                    'onchange'=>"updateEstimationPrice()"
                ]
            ]);
        if($valueComble){
            $builder->add('type', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\TypeCharpente',
                'choice_label' => 'nom',
                'multiple'     => false,
                'label' => 'Type',
                'required' => true,
                'query_builder' => function (TypeCharpenteRepository $er) use ($typeFermette) {
                    return $er->createQueryBuilder("u")
                        ->where('u.nom != :nomTypeFermette')
                        ->setParameters(array('nomTypeFermette' => $typeFermette->getNom()))
                        ;},
                'attr' => [
                    'onChange'=>"showPriceType(),updateEstimationPrice()"
                ]
            ]);
        }else{
            $builder->add('type', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\TypeCharpente',
                'choice_label' => 'nom',
                'multiple'     => false,
                'label' => 'Type',
                'required' => true,
                'attr' => [
                    'onChange'=>"showPriceType(),updateEstimationPrice()"
                ]
            ]);
        }
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\BuildBundle\Entity\Charpente'
        ))
            ->setRequired('valueComble')
            ->setRequired('typeFermette')
            ->setRequired('valueMainDoeuvre');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_charpente';
    }


}
