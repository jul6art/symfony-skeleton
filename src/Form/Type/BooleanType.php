<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Form\Type;

use App\Validator\Constraints\Boolean;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BooleanType.
 */
class BooleanType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'expanded' => true,
            'no_float' => true,
            'choices' => [
                'form.common.boolean.choices.yes' => 1,
                'form.common.boolean.choices.no' => 0,
            ],
            'choice_translation_domain' => 'form',
            'constraints' => [
                new Boolean(),
            ],
        ]);
    }

    /**
     * @return string|null
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
