<?php
namespace HomeConstruct\FoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'contact.lastname'],
            ])
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'contact.name'],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'contact.email'],
            ])
            ->add('phone', 	TelType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'contact.phone'],
            ])
            ->add('subject', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'contact.subject'],
            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'contact.message'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'contact.save',
                'attr' => ['class' => 'btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'HomeConstructFoBundle'
        ]);
    }
}
