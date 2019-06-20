<?php

namespace App\Form\Type;

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
	public function configureOptions(OptionsResolver $resolver) {
		parent::configureOptions($resolver);

		$resolver->setDefaults([
			'widget' => 'single_text',
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
    public function getBlockPrefix() {
	    return 'time_picker';
    }
}
