<?php

namespace Yeomi\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserPasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('username')
            ->remove('gender')
            ->remove('email');
    }
    

    /**
     * @return string
     */
    public function getName()
    {
        return 'yeomi_userbundle_user_password';
    }

    /**
     * @return UserType
     */
    public function getParent()
    {
        return new UserType();
    }
}
