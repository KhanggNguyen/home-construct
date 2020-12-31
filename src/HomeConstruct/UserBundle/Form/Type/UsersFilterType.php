<?php

namespace HomeConstruct\UserBundle\Form\Type;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\BooleanFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\NumberFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextFilterType::class, array('label' => 'E-mail'))
            ->add('enabled', BooleanFilterType::class, array('label' => 'Autorisé'))
            ->add('groups', EntityFilterType::class, array(
                'label' => 'Groupes',
                'class' => 'HomeConstruct\UserBundle\Entity\Group',
                'expanded' => true,
                'multiple' => true,
                'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                    $query = $filterQuery->getQueryBuilder();
                    $query->leftJoin($field, 'm');
                    // Filter results using orWhere matching ID
                    foreach ($values['value'] as $value) {
                        $query->orWhere($query->expr()->in('m.id', $value->getId()));
                    }
                },
            ))
        ;
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'HomeConstruct\UserBundle\Entity\Users',
            'csrf_protection'   => false,
            'validation_groups' => array('filter'),
            'method'            => 'GET',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'users_filter';
    }
}
