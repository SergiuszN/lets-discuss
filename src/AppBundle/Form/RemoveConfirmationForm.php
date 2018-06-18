<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemoveConfirmationForm extends AbstractType
{
    /**
     * Build remove confirmation form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cancel', ButtonType::class, array('label' => 'Cancel', 'attr' => [
                'style' => 'float: left; margin-right: 10px',
                'onClick' => 'javascript:window.history.go(-1)',
            ]))
            ->add('remove', SubmitType::class, array('label' => 'Remove', 'attr' => [
                'class' => 'btn btn-danger'
            ]));
    }

    /**
     * Configure remove confirmation form options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {

    }

    /**
     * Get remove confirmation form BlockPrefix
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_bundle_remove_confirmation_form';
    }
}
