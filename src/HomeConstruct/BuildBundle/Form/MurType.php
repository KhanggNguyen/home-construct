<?php

namespace HomeConstruct\BuildBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('longueur', NumberType::class, [
                'label' => 'Longueur du mur *',
                'required' => true,
                'attr' => array(
                    'maxLength' => '64',
                    'step'=>"1",
                    'min'=> '0'
                )
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom du Mur ',
                'required' => false,
            ])
            ->add('hauteur', NumberType::class, [
                'label' => 'Hauteur Mur *',
                'required' => true,
                'attr' => array(
                    'maxLength' => '64',
                    'step'=>"1",
                    'min'=> '0'
                )
            ])
            ->add('type', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\TypeMateriauxMur',
                'choice_label' => 'nom',
                'multiple'     => false,
                'label' => 'Matériaux du Mur *',
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
            'data_class' => 'HomeConstruct\BuildBundle\Entity\Mur'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_mur';
    }


}
