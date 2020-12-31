<?php

namespace HomeConstruct\BuildBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReseauEauUseeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*$valuePompe = $options['valuePompe'];
        $valueFosse = $options['valueFosse'];
        $valueStation = $options['valueStation'];
        $valueEtude = $options['valueEtude'];*/
        $builder
            ->add('pompeRelevage', ChoiceType::class, [
                'label' => 'Présence d\'une pompe de relevage : *',
                'required' => true,
                'choices'  => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'data' => null,
                'expanded' => true,
                'multiple' => false,
                'attr'=>[
                    'onchange'=>"prixAssainissement(),prixTotal()"
                ]
            ])
            ->add('fosseSeptique', ChoiceType::class, [
                'label' => 'Présence d\'une fosse septique : *',
                'required' => true,
                'choices'  => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'data' => null,
                'expanded' => true,
                'multiple' => false,
                'attr'=>[
                    'onchange'=>"prixAssainissement(),prixTotal()"
                ]
            ])
            ->add('microStation', ChoiceType::class, [
                'label' => 'Présence d\'une micro station : *',
                'required' => true,
                'choices'  => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'data' => null,
                'expanded' => true,
                'multiple' => false,
                'attr'=>[
                    'onchange'=>"prixAssainissement(),prixTotal()"
                ]
            ])
            ->add('etudeHydro', ChoiceType::class, [
                'label' => 'Etude hydrogéologique : *',
                'required' => true,
                'choices'  => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'data' => null,
                'expanded' => true,
                'multiple' => false,
                'attr'=>[
                    'onchange'=>"prixAssainissement(),prixTotal()"
                ]
            ])
            ->remove('prix');
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\BuildBundle\Entity\ReseauEauUsee'
        ));
        /*->setRequired('valuePompe')
        ->setRequired('valueFosse')
        ->setRequired('valueStation')
        ->setRequired('valueEtude');*/
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_eauusee';
    }


}
