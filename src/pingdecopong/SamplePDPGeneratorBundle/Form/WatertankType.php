<?php

namespace pingdecopong\SamplePDPGeneratorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WatertankType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', 'text', array(
                'label'     => 'Name',
                'required'  => false,
            ))
            ->add('SystemUserId', 'text', array(
                'label'     => 'SystemUserId',
                'required'  => false,
            ))
            ->add('IntegerData', 'text', array(
                'label'     => 'IntegerData',
                'required'  => false,
            ))
            ->add('DefTest1', 'checkbox', array(
                'label'     => 'DefTest1',
                'required'  => false,
            ))
            ->add('ColumnText', 'textarea', array(
                'label'     => 'ColumnText',
                'required'  => false,
            ))
                ->add('ColumnDate', 'date', array(
                'label'     => 'ColumnDate',
                'required'  => false,
                ))
                ->add('ColumnDatetime', 'datetime', array(
                'label'     => 'ColumnDatetime',
                'required'  => false,
                ))
                ->add('ColumnTime', 'time', array(
                'label'     => 'ColumnTime',
                'required'  => false,
                ))
        
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
        return 'Watertank';
    }
}
