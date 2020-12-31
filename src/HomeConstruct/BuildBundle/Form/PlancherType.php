<?php

namespace HomeConstruct\BuildBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PlancherType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nbM2', NumberType::class, [
                'label' => 'Surface à remplir *',
                'required' => true,
                'attr' => array(
                    'maxLength' => '500',
                    'step'=>"1",
                    'min'=> '0'
                )
            ])
            ->add('longueurPoutrelle', NumberType::class, [
                'label' => 'Longueur des poutrelles *',
                'required' => true,
                'attr' => array(
                    'maxLength' => '500',
                    'step'=>"1",
                    'min'=> '0'
                )
            ])
            ->add('longueurEntrevous', NumberType::class, [
                'label' => 'Longueur des Entrevous *',
                'required' => true,
                'attr' => array(
                    'maxLength' => '500',
                    'step'=>"1",
                    'min'=> '0'
                )
            ])

            ->add ('type')
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
            'data_class' => 'HomeConstruct\BuildBundle\Entity\Plancher'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_plancher';
    }


}
