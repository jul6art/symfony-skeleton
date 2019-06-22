<?php

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType as BaseType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChoiceType.
 */
class ChoiceType extends AbstractType
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
        return 'custom_choice';
    }
}
