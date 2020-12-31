<?php

namespace HomeConstruct\BuildBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HomeConstruct\BuildBundle\Form\DimensionMenuiserieInterieureType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MenuiserieInterieureType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\TypeMenuiserieInterieure',
                'choice_label' => 'nom',
                'multiple'     => false,
                'label' => 'Type *',
                'required' => true
            ])
            ->add('dimension', DimensionMenuiserieInterieureType::class)
            ->add('materiaux', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\MateriauxMenuiserieInterieure',
                'choice_label' => 'nom',
                'multiple'     => false,
                'label' => 'Matériaux *',
                'required' => true
            ])
            ->add('quantite', IntegerType::class, [
                'label' => 'Quantité *',
                'required' => true,
                'attr' => array(
                    'maxLength' => '64',
                    'step'=>"1",
                    'min'=> '1'
                )
            ])
            ->remove('prix')
            ->remove('secondsOeuvres')
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => ['class' => 'bouton_enregistrer btn btn-xl u-btn-lightblue-v3 g-width-160--md g-font-size-default g-ml-10']
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\BuildBundle\Entity\MenuiserieInterieure'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_menuiserieinterieure';
    }


}
