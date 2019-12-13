<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType as BaseType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CheckboxType.
 */
class CheckboxType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'no_float' => true,
        ]);
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
        return 'custom_checkbox';
    }
}
