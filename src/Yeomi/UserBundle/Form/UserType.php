<?php

namespace Yeomi\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', 'choice', array(
                'choices'   => array('m' => 'Masculin', 'f' => 'Féminin'),
                'required'  => true,
                'error_bubbling'=>true,
                'expanded' => true
            ))
            ->add('newsletter', 'checkbox', array(
                'required' => false,
                'label' => "S'inscrire à la newsletter",
                'mapped' => false,
            ))
            ->add('username', 'text', array('error_bubbling'=>true))
            ->add('passwordClear', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Les mots de passes doivent correspondre',
                'options' => array('required' => true),
                'first_options' => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Mot de passe (confirmation)'),
                'error_bubbling'=>true,
            ))
            ->add('email', 'email', array('error_bubbling'=>true))
            ->add('submit', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Yeomi\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'yeomi_userbundle_user';
    }
}
