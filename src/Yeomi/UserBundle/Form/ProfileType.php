<?php

namespace Yeomi\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $currentYear = date("Y");
        $builder
            ->add('avatar', new AvatarType(), array("required" => false))
            ->add('firstName', 'text', array("required" => false))
            ->add('lastName', 'text', array("required" => false))
            ->add('location', 'text', array("required" => false))
            ->add('birthday', 'date', array(
                "years" => range(1940,$currentYear),
                "required" => false,
            ))
            ->add('description', 'textarea', array("required" => false))
            ->add('job', 'text', array("required" => false))
            ->add('hobbies', 'text', array("required" => false))
            ->add('website', 'text', array("required" => false))
            ->add('save', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Yeomi\UserBundle\Entity\Profile'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'yeomi_userbundle_profile';
    }
}
