<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

		]);
	}

	/**
     * @return string|null
     */
    public function getParent()
    {
        return TextType::class;
    }

	/**
	 * @return string
	 */
    public function getBlockPrefix() {
	    return 'datetime_picker';
    }
}
