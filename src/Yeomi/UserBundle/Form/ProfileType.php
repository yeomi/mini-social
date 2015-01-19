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
        $builder
            ->add('avatar', new AvatarType(), array("required" => false))
            ->add('firstName', 'text')
            ->add('lastName', 'text')
            ->add('location', 'text')
            ->add('birthday', 'date')
            ->add('description', 'textarea')
            ->add('job', 'text')
            ->add('hobbies', 'text')
            ->add('website', 'url')
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
