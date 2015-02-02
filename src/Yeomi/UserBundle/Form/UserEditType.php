<?php

namespace Yeomi\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('passwordClear');
        $builder->remove('gender');
    }
    

    /**
     * @return string
     */
    public function getName()
    {
        return 'yeomi_userbundle_user_edit';
    }

    /**
     * @return UserType
     */
    public function getParent()
    {
        return new UserType();
    }
}
