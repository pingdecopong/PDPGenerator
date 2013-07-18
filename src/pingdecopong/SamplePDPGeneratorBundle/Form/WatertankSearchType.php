<?php

namespace pingdecopong\SamplePDPGeneratorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WatertankSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name')
            ->add('SystemUserId')
            ->add('IntegerData')
            ->add('DefTest1')
            ->add('systemuser')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'pingdecopong\SamplePDPGeneratorBundle\Entity\Watertank'
        ));
    }

    public function getName()
    {
        return 'pingdecopong_samplepdpgeneratorbundle_watertanksearchtype';
    }
}
