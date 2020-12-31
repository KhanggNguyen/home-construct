<?php

namespace HomeConstruct\BuildBundle\Form;


use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PoutreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('largeur', NumberType::class, [
                'label' => 'Largeur poutre *',
                'required' => true,
                'attr' => array(
                    'maxLength' => '64',
                    'step'=>"1",
                    'min'=> '0'
                )
            ])
            ->add('longueur', NumberType::class, [
                'label' => 'Hauteur Poutre *',
                'required' => true,
                'attr' => array(
                    'maxLength' => '64',
                    'step'=>"1",
                    'min'=> '0'
                )
            ])
            ->add('type', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\TypeMateriauxPoutre',
                'choice_label' => 'nom',
                'multiple'     => false,
                'label' => 'Matériaux des Poutres *',
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
            'data_class' => 'HomeConstruct\BuildBundle\Entity\Poutre'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_poutre';
    }


}
