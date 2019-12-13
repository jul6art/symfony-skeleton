<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Form\Type;

use App\Validator\Constraints\Datetime;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TimePickerType.
 */
class TimePickerType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'widget' => 'single_text',
            'constraints' => [
                new Datetime(),
            ],
        ]);
    }

    /**
     * @return string|null
     */
    public function getParent()
    {
        return TimeType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'time_picker';
    }
}
