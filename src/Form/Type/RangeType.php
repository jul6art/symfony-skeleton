<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RangeType.
 */
class RangeType extends AbstractType
{
	/**
	 * @param FormView      $view
	 * @param FormInterface $form
	 * @param array         $options
	 */
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		$view->vars['min'] = $options['min'];
		$view->vars['max'] = $options['max'];
		$view->vars['step'] = $options['step'];
	}

	/**
	 * @param OptionsResolver $resolver
	 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        	'min' => 0,
        	'max' => 100,
        	'step' => 10,
        ]);
    }
}
