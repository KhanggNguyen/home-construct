<?php

namespace HomeConstruct\UserBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('name',TextType::class, [ //champ de type texte (semblable à varchar)
            'label' => 'Nom *', //label du champ
            'required'=>true, //le champ est obligatoire
            'attr' => array('maxLength' => '45') //longueur maximale 45 (caractères)
            ])
            ->add('description', TextareaType::class, [ //champ de type textarea (zone de texte)
                'label' => 'Description *', //label du champ
                'attr' => [
                    'placeholder' => 'Ecrivez votre description ici',  //texte transparant contenu par défaut
                    'maxLength'=>'255' //longueur maximale 255 (caractères)
                ],
                'required' => true //le champ est obligatoire
            ])
            ->add('users', EntityType::class, [
                'class' => 'HomeConstruct\UserBundle\Entity\Users',
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'email'
            ])
            ->add('roles_global', EntityType::class, [
                'class' => 'HomeConstruct\UserBundle\Entity\Role',
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'name'
            ])
            ->add('save', SubmitType::class, [ //bouton d'enregistrement de l'entité
                'label' => 'Enregistrer',
                'attr' => ['class' => 'bouton_enregistrer btn btn-xl u-btn-lightblue-v3 g-width-160--md g-font-size-default g-ml-10']
            ])
            ->add('cancel',ResetType::class,[ //bouton de remise à zéro des champs
                'label' => 'Annuler',
                'attr' => ['class' => 'bouton_annuler btn btn-xl u-btn-lightblue-v3 g-width-160--md g-font-size-default g-ml-10']
            ]);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\GroupFormType';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\UserBundle\Entity\Group',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'group';
    }
}
