<?php

namespace HomeConstruct\BuildBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use HomeConstruct\BuildBundle\Entity\MenuiserieExterieure;
use HomeConstruct\BuildBundle\Entity\EnduitExterieur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use HomeConstruct\BuildBundle\Entity\Isolation;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class SecondOeuvreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('isolation', EntityType::class, [
                    'class'        => 'HomeConstruct\BuildBundle\Entity\Isolation',
                    'choice_label' => 'nom',
                    'label' => 'Isolation',
                    'multiple'     => false,
                    'required' => true
                    ])
                ->add('enduitExterieur', EntityType::class, [
                    'class'        => 'HomeConstruct\BuildBundle\Entity\EnduitExterieur',
                    'choice_label' => 'nom',
                    'label' => 'Enduit Extérieur',
                    'multiple'     => false,
                    'required' => true
                    ])
                ->add('save', SubmitType::class, [
                'label' => 'Sauvergarder',
                'attr' => ['class' => 'bouton_enregistrer btn btn-xl u-btn-lightblue-v3 g-width-160--md g-font-size-default g-ml-10']
                ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\BuildBundle\Entity\SecondOeuvre'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_secondoeuvre';
    }


}
