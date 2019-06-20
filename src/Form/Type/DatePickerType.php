<?php

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DatePickerType.
 */
class DatePickerType extends AbstractType
{
	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		parent::configureOptions($resolver);

		$resolver->setDefaults([
			'widget' => 'single_text',
			'format' => 'dd-MM-yyyy',
		]);
	}

	/**
     * @return string|null
     */
    public function getParent()
    {
        return DateType::class;
    }

	/**
	 * @return string
	 */
    public function getBlockPrefix() {
	    return 'date_picker';
    }
}
