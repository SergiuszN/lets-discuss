<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyWorkerForm extends AbstractType
{
    /**
     * Build company worker form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('required' => true, 'label' => 'Worker name:'))
            ->add('surname', TextType::class, array('required' => true, 'label' => 'Worker surname:'))
            ->add('save', SubmitType::class, array('label' => 'Create worker', 'attr' => ['class' => 'btn btn-primary']));
    }

    /**
     * Configure company worker form options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {

    }

    /**
     * Get company worker form BlockPrefix
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_bundle_company_worker';
    }
}
