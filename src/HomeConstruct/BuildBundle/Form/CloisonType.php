<?php

namespace HomeConstruct\BuildBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CloisonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cloison', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\Cloison',
                'choice_label' => 'nom',
                'multiple'     => false,
                'label' => 'Type de cloison',
                'required' => false
            ])
            ->add('cloisonAmovible', CheckboxType::class, [
                'label' => 'Cloison amovible',
                'required' => false
            ])
            ->add('prixM2');
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\BuildBundle\Entity\Cloison'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_cloison';
    }


}
