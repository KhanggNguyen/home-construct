<?php


namespace HomeConstruct\BuildBundle\Form;

use HomeConstruct\BuildBundle\Entity\TypeRevetementSol;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RevetementSolType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\TypeRevetementSol',
                'choice_label' => 'nom',
                'multiple'     => false,
                'label' => 'Type : *',
                'required' => true
            ])
            ->add('quantiteM2', IntegerType::class, [
                'label' => 'Quantité (en m²) : *',
                'required' => true,
                'attr' => array(
                    'maxLength' => '64',
                    'step'=>"1",
                    'min'=> '1'
                )
            ])
            ->remove('prix')
            ->remove('secondOeuvre');
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\BuildBundle\Entity\RevetementSol'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_revetement_sol';
    }


}
