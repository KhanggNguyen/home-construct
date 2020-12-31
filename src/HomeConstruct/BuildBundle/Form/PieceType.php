<?php

namespace HomeConstruct\BuildBundle\Form;

use HomeConstruct\BuildBundle\Entity\RevetementSolPiece;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PieceType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $piece = $options['piece'];
        $builder
            ->add('nom',TextType::class, [
                'label' => 'Nom de la pièce : *',
                'attr' => array('maxLength' => '64'),
                'required'=>true,
            ])
            ->add('surface', NumberType::class, [
            'label' => 'Surface (en m²) : ',
            'required' => false,
            'attr' => array(
                'maxLength' => '64',
                'step'=>"0.01",
                'min'=> '0'
                )
            ])
            ->add('salleDeau', CheckboxType::class, [
                'label' => 'Salle d\'eau',
                'required' => false
            ])
            ->add('cloison', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\Cloison',
                'choice_label' => 'nom',
                'multiple'     => false,
                'label' => 'Type de cloison : ',
                'required' => true
            ])
            ->add('cloisonAmovible', CheckboxType::class, [
                'label' => 'Cloison amovible',
                'required' => false
            ])
            ->add('isolation', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\TypeIsolationPiece',
                'choice_label' => 'nom',
                'multiple'     => false,
                'label' => 'Type d\'isolation : ',
                'required' => false
            ]);
        if($piece->getChauffage()){
            $builder->add('chauffage', ChauffageType::class,[
                'label' => 'Chauffage',
                'label_attr' => array(
                    'id'=>'label_chauffage',
                    'class' => 'hs-admin-angle-down sub_title_form btn btn-xl g-width-180--md g-font-size-default',
                    'onclick'=>"showBlockChauffage()"
                ),
                'required'=>false
            ]);
        }else{
            $builder->add('chauffage', ChauffagePieceType::class,[
                'label' => 'Chauffage',
                'label_attr' => array(
                    'id'=>'label_chauffage',
                    'class' => 'hs-admin-angle-down sub_title_form btn btn-xl g-width-180--md g-font-size-default',
                    'onclick'=>"showBlockChauffage()"
                ),
                'required'=>false
            ]);
        }
        if($piece->getVentilation()){
            $builder->add('ventilation', VentilationType::class,[
                'label' => 'Ventilation',
                'label_attr' => array(
                    'id'=>'label_ventilation',
                    'class' => 'hs-admin-angle-down sub_title_form btn btn-xl u-btn-bluegray g-width-180--md g-font-size-default',
                    'onclick'=>"showBlockVentilation()"
                ),
                'required'=>false
            ]);
        }else{
            $builder->add('ventilation', VentilationPieceType::class,[
                'label' => 'Ventilation',
                'label_attr' => array(
                    'id'=>'label_ventilation',
                    'class' => 'hs-admin-angle-down sub_title_form btn btn-xl u-btn-bluegray g-width-180--md g-font-size-default',
                    'onclick'=>"showBlockVentilation()"
                ),
                'required'=>false
            ]);
        }
        if($piece->getClimatisation()){
            $builder->add('climatisation', ClimatisationType::class,[
                'label' => 'Climatisation',
                'label_attr' => array(
                    'id'=>'label_climatisation',
                    'class' => 'hs-admin-angle-down sub_title_form btn btn-xl u-btn-bluegray g-width-180--md g-font-size-default',
                    'onclick'=>"showBlockClimatisation()"
                ),
                'required'=>false
            ]);
        }else{
            $builder->add('climatisation', ClimatisationPieceType::class,[
                'label' => 'Climatisation',
                'label_attr' => array(
                    'id'=>'label_climatisation',
                    'class' => 'hs-admin-angle-down sub_title_form btn btn-xl u-btn-bluegray g-width-180--md g-font-size-default',
                    'onclick'=>"showBlockClimatisation()"
                ),
                'required'=>false
            ]);
        }
        if($piece->getRevetementSol()) {
            $builder->add('revetementSol', RevetementSolType::class,[
                'label' => 'Revêtement du sol',
                'label_attr' => array(
                    'id'=>'label_revetementSol',
                    'class' => 'hs-admin-angle-down sub_title_form btn btn-xl u-btn-bluegray g-width-180--md g-font-size-default',
                    'onclick'=>"showBlockRevetementSol()"
                ),
                'required'=>false
            ]);
        }else{
            $builder->add('revetementSol', RevetementSolPieceType::class,[
                'label' => 'Revêtement du sol',
                'label_attr' => array(
                    'id'=>'label_revetementSol',
                    'class' => 'hs-admin-angle-down sub_title_form btn btn-xl u-btn-bluegray g-width-180--md g-font-size-default',
                    'onclick'=>"showBlockRevetementSol()"
                ),
                'required'=>false
            ]);
        }
        $builder->remove('grosOeuvre');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\BuildBundle\Entity\Piece'
        ))->setRequired('piece');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_piece';
    }


}
