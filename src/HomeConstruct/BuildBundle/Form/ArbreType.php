<?php

namespace HomeConstruct\BuildBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use HomeConstruct\BuildBundle\Entity\Arbre;
use HomeConstruct\BuildBundle\Entity\TailleArbre;
use HomeConstruct\BuildBundle\Form\TailleArbreType;
use HomeConstruct\BuildBundle\Form\TypeArbreType;

class ArbreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $valeurDessouchage = $options['valeurDessouchage'];
        $valeurAbattageNettoyage = $options['valeurAbattageNettoyage'];
        $builder
            ->add('typeArbre', EntityType::class,[
                'class' => 'HomeConstruct\BuildBundle\Entity\TypeArbre',
                'label' => 'Type de l\'arbre *',
                'placeholder' => '---Selectionner un option---',
                'attr' => [

                ]
            ])
            ->add('tailleArbre', EntityType::class, [
                'class' => 'HomeConstruct\BuildBundle\Entity\TailleArbre',
                'label' => 'Taille de l\'arbre *',
                'placeholder' => '---Selectionner un option---',
                'attr' => [

                ]
            ])
            ->add('prixAbattageNettoyage', RangeType::class, [
                'label' => 'Prix Abattage et Nettoyage *',
                'required' => true,
                'attr' => [
                    'value'=>$valeurAbattageNettoyage,
                    'step'=>1,
                ]
            ])
            ->add('prixDessouchage', RangeType::class, [
                'label' => 'Prix Dessouchage *',
                'required' => true,
                'attr' => [
                    'value'=>$valeurDessouchage,
                    'step'=>1,
                ]
            ])
            ->add('quantite', IntegerType::class, [
                    'label' => 'Quantité *',
                    'required' => true,
                    'attr' => array(
                        'step'=>"1",
                        'min'=> '1')
                    ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'bouton_enregistrer btn btn-xl u-btn-lightblue-v3 g-width-160--md g-font-size-default g-ml-10']
            ])
            ->remove('elagageArbre');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\BuildBundle\Entity\Arbre'
        ))
            ->setRequired('valeurDessouchage')
            ->setRequired('valeurAbattageNettoyage')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_arbre';
    }


}
