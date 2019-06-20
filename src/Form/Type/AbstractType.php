<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType as BaseType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BooleanType.
 */
abstract class AbstractType extends BaseType
{
	/**
	 * @param FormView $view
	 * @param FormInterface $form
	 * @param array $options
	 */
	public function buildView(FormView $view, FormInterface $form, array $options) {
		$view->vars['addon_left'] = $options['addon_left'];
		$view->vars['addon_right'] = $options['addon_right'];
		$view->vars['alert'] = $options['alert'];
		$view->vars['alert_class'] = $options['alert_class'];
		$view->vars['exploded'] = $options['exploded'];
		$view->vars['mask'] = $options['mask'];
		$view->vars['no_float'] = $options['no_float'];
		$view->vars['pattern'] = $options['pattern'];
		$view->vars['tooltip'] = $options['tooltip'];
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		parent::configureOptions($resolver);

		$resolver->setDefined([
			'addon_left',
			'addon_right',
			'alert',
			'alert_class',
			'exploded',
			'mask',
			'no_float',
			'pattern',
			'tooltip',
		]);

		$resolver->setDefaults([
			'addon_left' => false,
			'addon_right' => false,
			'alert' => false,
			'alert_class' => false,
			'exploded' => false,
			'mask' => false,
			'no_float' => false,
			'pattern' => false,
			'tooltip' => false,
		]);
	}
}
