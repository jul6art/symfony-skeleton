<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PhoneType.
 */
class PhoneType extends AbstractType
{
	/**
	 * @param FormView $view
	 * @param FormInterface $form
	 * @param array $options
	 */
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		parent::buildView($view, $form, $options);

		$view->vars['no_float'] = $options['no_float'];
	}

    /**
     * @return string|null
     */
    public function configureOptions(OptionsResolver $resolver) {
	    parent::configureOptions( $resolver );

	    $resolver->setDefaults([
	    	'no_float' => true,
	    	'attr' => [
	    		'class' => 'input-phone',
		    ],
	    ]);
    }

	/**
	 * @return null|string
	 */
    public function getParent() {
	    return TextType::class;
    }
}
