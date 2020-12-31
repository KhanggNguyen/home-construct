<?php

namespace HomeConstruct\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        parent::buildForm($builder, $options);
        $builder->remove('username');
//        $builder
//            ->add('name', TextType::class, array(
//                    'label' => 'Nom',
//                    'required'=> true,
//                    'attr' => array(),
//                )
//            )
//            ->add('firstname', TextType::class, array(
//                    'label' => 'Prénom',
//                    'required'=> true,
//                    'attr' => array(),
//                )
//            )
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getName()
    {
        return 'home_construct_user_registration';
    }
}
