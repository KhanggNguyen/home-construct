<?php
namespace HomeConstruct\BuildBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class TerrassementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('longueur', NumberType::class, [
                'label' => 'Longueur * :',
                'required' => true,
                'attr' => array(
                    'step'=>"0.01",
                    'min'=> '0.01'
                )
            ])
            ->add('largeur', NumberType::class, [
                'label' => 'Largeur * :',
                'required' => true,
                'attr' => array(
                    'step'=>"0.01",
                    'min'=> '0.01'
                )
            ])
            ->add('profondeur', NumberType::class, [
                'label' => 'Profondeur :',
                'required' => true,
                'attr' => array(
                    'step'=>"0.01",
                    'min'=> '0'
                )
            ])
            ->add('altimetrie', NumberType::class, [
                'label' => 'Altimetrie :',
                'required' => true,
                'attr' => array(
                    'step'=>"0.01",
                    'min'=> '0'
                )
            ])
            ->add('travaux', EntityType::class, [
                'class' => 'HomeConstruct\BuildBundle\Entity\Travaux',
                'label' => 'Type de travaux *',
                'placeholder' => '---Selectionner un option---',
                'attr' => [

                ]
            ])
            ->add('evacuation', EntityType::class, [
                'class' => 'HomeConstruct\BuildBundle\Entity\EvacuationTerrassement',
                'label' => 'Type d\'evacuation *',
                'placeholder' => '---Selectionner un option---',
                'attr' => [

                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'bouton_enregistrer btn btn-xl u-btn-lightblue-v3 g-width-160--md g-font-size-default g-ml-10']
            ])
            ->remove('prix');
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\BuildBundle\Entity\Terrassement'
        ));
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_terrassement';
    }
}
?>