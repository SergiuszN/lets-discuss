<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppraiseForm extends AbstractType
{
    /**
     * Build appraise form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rate', ChoiceType::class, array(
                'required' => true,
                'label' => 'Rate',
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                )
            ))
            ->add('description', TextareaType::class, array('required' => false, 'label' => 'Description'))
            ->add('save', SubmitType::class, array('label' => 'Add appraise'));
    }

    /**
     * Configure appraise form options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {

    }

    /**
     * Get appraise form BlockPrefix
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_bundle_appraise_form';
    }
}
