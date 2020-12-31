<?php

namespace HomeConstruct\UserBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class UserSuperAdminType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $groupeSuperAdmin = empty($options['groupeSuperAdmin']) ? '' : $options['groupeSuperAdmin'];
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom *',
                'attr' => array('maxLength' => '45')
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom *',
                'attr' => array('maxLength' => '45')
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email *',
                'attr' => ['type' => 'email','maxLength' => '254']
            ])
            ->add('groups', EntityType::class, [
                'label' => 'Groupe',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
                'data'=> $groupeSuperAdmin,
                'choice_label'=>'name',
                'class' => 'HomeConstruct\UserBundle\Entity\Groupe'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'bouton_enregistrer btn btn-xl u-btn-lightblue-v3 g-width-160--md g-font-size-default g-ml-10']
            ])
            ->add('cancel',ResetType::class,[
                'label' => 'Annuler',
                'attr' => ['class' => 'bouton_annuler btn btn-xl u-btn-lightblue-v3 g-width-160--md g-font-size-default g-ml-10']
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\UserBundle\Entity\Users'
        ))
            ->setRequired('groupeSuperAdmin');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_userbundle_user_add_super_admin';
    }


}
