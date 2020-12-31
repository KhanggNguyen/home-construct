<?php

namespace HomeConstruct\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PermissionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('name')
            ->add('path')
            ->add('pathUrl')
            ->add('isPrefixPublic')
            ->add('isPrimary')
            ->add('showOrder')
            ->add('iconMenu')
            ->add('roles')
            ->add('permissionGroupId')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\UserBundle\Entity\Permission',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'permission';
    }
}
