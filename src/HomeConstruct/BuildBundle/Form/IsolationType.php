<?php

namespace HomeConstruct\BuildBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class IsolationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('typeIsolation', ChoiceType::class, [
                'label' => 'Type d\'isolation : *',
                'required' => true,
                'choices'  => [
                    'Thermique' => 'thermique',
                    'Phonique' => 'phonique',
                ],
            ])
            ->add('surfaceMursInterieur', NumberType::class,[
                'label' => 'Surface du mur intérieur :',
                'required' => false,
                'attr' => array(
                    'maxLength' => '10',
                    'step' => '0.01',
                    'min' => '0'
                )
            ])
            ->add('surfaceMursExterieur',NumberType::class,[
                'label' => 'Surface du mur extérieur : ',
                'required' => false,
                'attr' => array(
                    'maxLength' => '10',
                    'step' => '0.01',
                    'min' => '0'
                )
            ])
            ->add('surfacePlafond', NumberType::class,[
                'label' => 'Surface du plafond :',
                'required' => false,
                'attr' => array(
                    'maxLength' => '10',
                    'step' => '0.01',
                    'min' => '0'
                )
            ])
            ->add('surfaceSol', NumberType::class,[
                'label' => 'Surface du sol :',
                'required' => false,
                'attr' => array(
                    'maxLength' => 10,
                    'step' => '0.01',
                    'min' => 0
                )
            ])
            ->add('typeIsolationMur', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\typeIsolationMur',
                'choice_label' => 'nom',
                'multiple'     => false,
                'placeholder' => '---Selectionner une valeur---',
                'label' => 'Type d\'isolation des murs :',
                'required' => false
            ])
            ->add('typeIsolationPlancher', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\typeIsolationPlancher',
                'choice_label' => 'nom',
                'multiple'     => false,
                'placeholder' => '---Selectionner une valeur---',
                'label' => 'Type d\'isolation des sols :',
                'required' => false
            ])
            ->add('typeIsolationPlafond', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\typeIsolationPlafond',
                'choice_label' => 'nom',
                'multiple'     => false,
                'placeholder' => '---Selectionner une valeur---',
                'label' => 'Type d\'isolation des plafonds :',
                'required' => false
            ])
            ->add('typeIsolationVitre', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\typeIsolationVitre',
                'choice_label' => 'nom',
                'multiple'     => false,
                'placeholder' => '---Selectionner une valeur---',
                'label' => 'Type d\'isolation des vitres :',
                'required' => false
            ])
            ->add('surfaceVitre', NumberType::class,[
                'label' => 'Surface des vitres :',
                'required' => false,
                'attr' => array(
                    'maxLength' => '10',
                    'step' => '0.01',
                    'min' => '0'
                )
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
            'data_class' => 'HomeConstruct\BuildBundle\Entity\Isolation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_isolation';
    }


}
