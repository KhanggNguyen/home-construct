<?php

namespace HomeConstruct\BuildBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AppuisFenetreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\TypeAppuisFenetre',
                'choice_label' => 'nom',
                'multiple'     => false,
                'label' => 'Matériaux des appuis fenetres*',
                'required' => true
            ])
            ->add('quantite', NumberType::class,[
                'label' => 'Nombre d\'Appuis Fenetre',
                'attr' => [
                    'disabled' => false,
                ]
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
            'data_class' => 'HomeConstruct\BuildBundle\Entity\AppuisFenetre'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_appuisfenetre';
    }


}
