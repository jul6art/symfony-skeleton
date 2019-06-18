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
		$view->vars['exploded'] = $options['exploded'];
		$view->vars['no_float'] = $options['no_float'];
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		parent::configureOptions($resolver);

		$resolver->setDefined([
			'exploded',
			'no_float',
		]);

		$resolver->setDefaults([
			'exploded' => false,
			'no_float' => false,
		]);
	}
}
