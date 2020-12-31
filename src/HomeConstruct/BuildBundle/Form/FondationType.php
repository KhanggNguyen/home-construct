<?php

namespace HomeConstruct\BuildBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FondationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $valueMainDoeuvre = $options['valueMainDoeuvre'];
        $user = $options['user'];
        $builder
            ->add('type', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\TypeFondation',
                'choice_label' => 'nom',
                'multiple'     => false,
                'label' => 'Type : *',
                'required' => true,
            ])
            ->add('prixMainDoeuvre', RangeType::class, [
                'label' => 'Tarif max de la main d\'oeuvre  : *',
                'required' => true,
                'attr' => [
                    'min' => 15,
                    'max' => 100,
                    'value'=>$valueMainDoeuvre,
                    'step'=>1,
                    'class'=>"slider",
                    'onchange'=>"prixTotalMainDoeuvre(),updatePrixTotal()"
                ]
            ]);
        if($user->hasGroup('PROFESSIONNEL')or $user->hasGroup('SUPER ADMIN')){
            $builder
                ->add('ferraillage', FerraillageType::class,[
                    'label_attr' => array('class' => 'sub_title_form'
                    )
                ]);
        }
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\BuildBundle\Entity\Fondation'
        ))
            ->setRequired('valueMainDoeuvre')
            ->setRequired('user')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_fondation';
    }


}
