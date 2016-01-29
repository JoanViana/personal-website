<?php

namespace JV\PersonalWebsiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email','email')
            ->add('subject')
            ->add('body', 'textarea')
            ->add('save', 'submit', array('label' => 'Send Feedback'))
        ;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'contact';
    }
}
