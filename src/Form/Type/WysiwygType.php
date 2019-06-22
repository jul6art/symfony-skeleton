<?php

namespace App\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class WysiwygType.
 */
class WysiwygType extends AbstractType
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
        return TextareaType::class;
    }
}
