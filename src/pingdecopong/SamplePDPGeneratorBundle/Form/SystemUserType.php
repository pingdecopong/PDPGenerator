<?php

namespace pingdecopong\SamplePDPGeneratorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SystemUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'pingdecopong\SamplePDPGeneratorBundle\Entity\SystemUser'
        ));
    }

    public function getName()
    {
        return 'SystemUser';
    }
}
