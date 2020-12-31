<?php


namespace HomeConstruct\BuildBundle\Form;

use HomeConstruct\BuildBundle\Entity\TypeClimatisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClimatisationPieceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', EntityType::class, [
                'class'        => 'HomeConstruct\BuildBundle\Entity\TypeClimatisation',
                'choice_label' => 'nom',
                'multiple'     => false,
                'label' => 'Type : *',
                'required' => false,
                'attr' => array(
                    'onchange'=>"updateInputRequiredClimatisation()"
                )
            ])
            ->add('quantite', IntegerType::class, [
                'label' => 'Quantité : *',
                'required' => false,
                'attr' => array(
                    'maxLength' => '64',
                    'step'=>"1",
                    'min'=> '1',
                    'oninput'=>"updateInputRequiredClimatisation()"
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
            'data_class' => 'HomeConstruct\BuildBundle\Entity\Climatisation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'homeconstruct_buildbundle_climatisationpiece';
    }


}
