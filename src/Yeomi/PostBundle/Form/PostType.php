<?php

namespace Yeomi\PostBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', 'textarea')
            ->add("categories", "entity", array(
                "class" => "YeomiPostBundle:Category",
                "property" => "name",
                'empty_value' => ' -- Choisissez une catégorie -- ',
            ))

            ->add('images', 'collection', array(
                'type' => new ImageType(),
                'allow_add' => false,
                'allow_delete' => false,
            ))
            ->add('save', "submit")
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Yeomi\PostBundle\Entity\Post',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'yeomi_postbundle_post';
    }
}
