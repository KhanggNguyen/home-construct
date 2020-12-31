<?php

namespace HomeConstruct\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PermissionGroupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('name')
            ->add('nameCanonical')
            ->add('showOrder')
            ->add('iconMenu')
            ->add('parentGroup')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\UserBundle\Entity\PermissionGroup',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'permissiongroup';
    }
}
