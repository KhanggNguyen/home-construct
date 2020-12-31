<?php

namespace HomeConstruct\UserBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use HomeConstruct\UserBundle\Repository\GroupeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $groupeSuperAdmin = empty($options['groupeSuperAdmin']) ? '' : $options['groupeSuperAdmin'];
        $groupeProfessionnel = empty($options['groupeProfessionnel']) ? '' : $options['groupeProfessionnel'];
        $builder
            ->add('name', TextType::class,
                array(
                    'required' => false,
                    'label' => 'Nom'
                )
            )
            ->add('firstname', TextType::class,
                array(
                    'required' => false,
                    'label' => 'Prénom'
                )
            )
            ->add('email', EmailType::class,
                array(
                    'required' => true,
                    'label' => 'E-mail'
                )
            )
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques.',
                'required' => $options['passwordRequired'],
                'options'=>array(
                    'attr'=>array(
                        'pattern'=>'(?=^.{8,}$)((?=.*d)|(?=.*W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$',
                        'title'=>'Le mot de passe doit contenir au minimum 8 caractères avec au moins 1 majuscule, 1 minuscule, 1 chiffre ou caractère spécial'
                    )
                ),
                'first_options' => array(
                    'label' => 'Mot de passe'
                ),
                'second_options' => array(
                    'label' => 'Répétez le mot de passe'),
            ))
            ->remove('groups')
            /*->add('groups', EntityType::class, array(
                'label' => 'Groupe',
                'multiple' => false,
                'data_class'=>null,
                'expanded' => false,
                'choice_label'=>'name',
                'choice_value'=>'id',
                'required' => true,
                'data'=>null,
                'class' => 'HomeConstruct\UserBundle\Entity\Groupe',
                'query_builder' => function (EntityRepository $er) use ($groupeSuperAdmin,$groupeProfessionnel) {
                    return $er->createQueryBuilder("u")
                        ->where(':groupeSuperAdmin != u.name')
                        ->andWhere(':groupeProfessionnel != u.name')
                        ->setParameters(array('groupeSuperAdmin'=>$groupeSuperAdmin->getName(),'groupeProfessionnel'=>$groupeProfessionnel->getName()));
                }
            ))*/
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'bouton_enregistrer btn btn-xl u-btn-lightblue-v3 g-width-160--md g-font-size-default g-ml-10']
            ])
            ->add('cancel',ResetType::class,[
                'label' => 'Annuler',
                'attr' => ['class' => 'bouton_annuler btn btn-xl u-btn-lightblue-v3 g-width-160--md g-font-size-default g-ml-10']
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeConstruct\UserBundle\Entity\Users',
            'passwordRequired' => true))
            ->setRequired('groupeSuperAdmin')
            ->setRequired('groupeProfessionnel');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'users';
    }
}
