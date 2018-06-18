<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyForm extends AbstractType
{
    /**
     * Build company form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('required' => true, 'label' => 'Company Name:'))
            ->add('description', TextareaType::class, array('required' => true, 'label' => 'Company Description:'))
            ->add('email', EmailType::class, array('required' => true, 'label' => 'Administrator Email:'))
            ->add('username', TextType::class, array('required' => true, 'label' => 'Administrator Username:'))
            ->add('save', SubmitType::class, array('label' => 'Create'));
    }

    /**
     * Configure company form options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {

    }

    /**
     * Get company form BlockPrefix
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_bundle_company_form';
    }
}
