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
            ->add('DefTest1', 'choice', array(
                'label'     => 'DefTest1',
                'choices'   => array('1' => '有効', '0' => '無効'),
                'required'  => false,
                'expanded'  => false,
                'multiple'  => false,
            ))
            ->add('ColumnText', 'text', array(
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
            'data_class' => 'pingdecopong\SamplePDPGeneratorBundle\Form\WatertankSearchModel'
        ));
    }

    public function getName()
    {
        return 'Watertank';
    }
}
