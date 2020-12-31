<?php

namespace HomeConstruct\BuildBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtudeSolType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('prixForfait', NumberType::class, [
                'label' => 'Prix du forfait *',
                'required' => true,
                'attr' => array(
                    'maxLength' => '64',
                    'step'=>"0.01",
                    'min'=> '0.01'
                )
            ])
            ->remove('dateCreation')
            ->add('type', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\TypeSol',
                'choice_label' => 'nom',
                'multiple'     => false,
                'label' => 'Type *',
                'required' => true,
                'attr' => array(
                    'onChange' => "profondeur(),testBonneProfondeur()"
                )
            ])
            ->add('profondeurChoisie', NumberType::class, [
                'label' => 'Profondeur choisie (en mètre) compris entre le minimum et le maximum recommandé : *',
                'required' => true,
                'attr' => array(
                    'maxLength' => '64',
                    'step'=>"0.01",
                    'min'=> '0.01',
                    'onChange' => "testBonneProfondeur()"
                )
            ])
            ->remove('grosOeuvre');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\BuildBundle\Entity\EtudeSol'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_etudesol';
    }


}
