<?php

namespace HomeConstruct\BuildBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExcavationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nbMetresMurPeriph', NumberType::class, [
                'label' => 'Nombre de mètres linéaires des murs périphériques : * ',
                'required' => true,
                'attr' => array(
                    'maxLength' => '64',
                    'step'=>"0.01",
                    'min'=> '0'
                )
            ])
            ->add('nbMetresMurRefont', NumberType::class, [
                'label' => 'Nombre de mètres linéaires des murs de refont : * ',
                'required' => true,
                'attr' => array(
                    'maxLength' => '64',
                    'step'=>"0.01",
                    'min'=> '0'
                )
            ])
            ->add('largeurFouille', NumberType::class, [
                'label' => 'Largeur des Fouilles : *',
                'required' => true,
                'attr' => array(
                    'maxLength' => '64',
                    'step' => '0.01',
                    'min' => '0'
                )
            ])
            ->add('profondeurFouille', NumberType::class, [
                'label' => 'Profondeur des Fouilles : *',
                'required' => true,
                'attr' => array(
                    'maxLength' => '10',
                    'step' => '0.01',
                    'min' => '0'
                )
            ])
            ->add('fouilleGarage', ChoiceType::class, [
                'label' => 'Garage plancher : *',
                'required' => true,
                'choices'  => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'data' => null,
                'expanded' => true,
                'multiple' => false
            ])
            ->remove('prixTotal')
            ->add('typeTerrassement', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\TypeTerrassement',
                'choice_label' => 'nom',
                'multiple'     => false,
                'placeholder' => '---Selectionner une valeur---',
                'label' => 'Type de terrassement avant fondation : *',
                'required' => true
            ])
            ->add('materiels', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\MaterielExcavation',
                'choice_label' => 'nom',
                'multiple'     => true,
                'label' => 'Matériel(s) utilisé(s) : *',
                'required' => true
            ]);
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\BuildBundle\Entity\Excavation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_excavation';
    }


}
