<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DatePickerType.
 */
class DatePickerType extends AbstractType
{
	/**
	 * @param FormView $view
	 * @param FormInterface $form
	 * @param array $options
	 */
	public function buildView( FormView $view, FormInterface $form, array $options ) {
		$view->vars['addon'] = $options['addon'];
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		parent::configureOptions($resolver);

		$resolver->setDefined(['addon']);

		$resolver->setDefaults([
			'addon' => false,
			'widget' => 'single_text',
			'format' => 'yyyy-MM-dd',
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
