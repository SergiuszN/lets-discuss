<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyManagerForm extends AbstractType
{
    /**
     * Build company manager form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array('required' => true, 'label' => 'Manager Email:'))
            ->add('username', TextType::class, array('required' => true, 'label' => 'Manager Username:'))
            ->add('save', SubmitType::class, array('label' => 'Create'));
    }

    /**
     * Configure company manager form options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {

    }

    /**
     * Get company manager form BlockPrefix
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_bundle_company_manager_form';
    }
}
