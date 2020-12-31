<?php

namespace HomeConstruct\BuildBundle\Form;

use HomeConstruct\BuildBundle\Entity\ReseauGazNaturel;
use HomeConstruct\BuildBundle\Entity\Vrd;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VrdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $valueGazNaturel = $options['valueGazNaturel'];
        $builder
            ->add('reseauEauPotable', ReseauEauPotableType::class,[
                'label' => 'Réseau eau potable',
                'label_attr' => array('class' => 'sub_title_form'),
                'required' => true
            ])
            ->add('reseauEauUsee', ReseauEauUseeType::class,[
                'label' => 'Réseau eau usée',
                'label_attr' => array('class' => 'sub_title_form'),
                'required' => true
            ])
            ->add('reseauElectrique', ReseauElectriqueType::class,[
                'label' => 'Réseau électrique',
                'label_attr' => array('class' => 'sub_title_form'),
                'required' => true
            ])
            ->add('reseauGazNaturel', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\ReseauGazNaturel',
                'choice_label' => 'nom',
                'multiple'     => false,
                'label' => 'Fournisseur :',
                'required' => false,
                'data'=>$valueGazNaturel,
                'attr'=>array(
                    'onchange'=>"prixGazNaturel(),prixTotal()"
                )
            ])
            ->add('prixReseauTelephonique', NumberType::class, [
                'label' => 'Prix du réseau téléphonique : *',
                'required' => true,
                'grouping'=>true,
                'attr' => array(
                    'maxLength' => '64',
                    'step'=>"0.01",
                    'min'=> '0.01',
                    'oninput'=>"prixTelephone(),prixTotal()"
                )
            ])
            ->remove('prixTotal');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'HomeConstruct\BuildBundle\Entity\Vrd'
        ])
            ->setRequired('valueGazNaturel');
           /* ->setRequired('valuePompe')
            ->setRequired('valueFosse')
            ->setRequired('valueStation')
            ->setRequired('valueEtude');*/
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_vrd';
    }
}
