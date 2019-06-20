<?php

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DatetimePickerType.
 */
class DatetimePickerType extends AbstractType
{
	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		parent::configureOptions($resolver);

		$resolver->setDefaults([
			'widget' => 'single_text',
			'format' => 'dd-MM-yyyy HH:mm',
		]);
	}

	/**
     * @return string|null
     */
    public function getParent()
    {
        return DateTimeType::class;
    }

	/**
	 * @return string
	 */
    public function getBlockPrefix() {
	    return 'datetime_picker';
    }
}
