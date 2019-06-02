<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Class SwitchType
 * @package App\Form\Test
 */
class SwitchType extends AbstractType
{
	/**
	 * @return null|string
	 */
    public function getParent() {
	    return CheckboxType::class;
    }
}
