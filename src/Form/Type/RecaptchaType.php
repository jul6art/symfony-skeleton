<?php

namespace App\Form\Type;

use App\Validator\Constraints\Recaptcha;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RecaptchaType.
 */
class RecaptchaType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver) {
	    parent::configureOptions( $resolver );

	    $resolver->setDefaults([
	    	'error_bubbling' => false,
	    	'no_float' => true,
		    'constraints' => [
		    	new Recaptcha(),
		    ],
	    ]);
    }
}
