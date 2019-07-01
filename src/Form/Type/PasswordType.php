<?php

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\PasswordType as BaseType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PasswordType.
 */
class PasswordType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
    }

    /**
     * @return string|null
     */
    public function getParent()
    {
        return BaseType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'custom_text';
    }
}
