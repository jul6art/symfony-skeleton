<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PhoneType.
 */
class PhoneType extends AbstractType
{
    /**
     * @return string|null
     */
    public function configureOptions(OptionsResolver $resolver) {
	    parent::configureOptions( $resolver );

	    $resolver->setDefaults([
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
