<?php

namespace Yeomi\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsletterSubscriptionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $email = $options['email'];

        if (is_null($email)) {
            $builder->add('email', 'email');
        } else {
            $builder->add('email', 'hidden', array(
                'data' => $email,
            ));
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Yeomi\AppBundle\Entity\NewsletterSubscription',
            'email' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'yeomi_appbundle_newslettersubscription';
    }
}
