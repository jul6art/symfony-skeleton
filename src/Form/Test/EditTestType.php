<?php

namespace App\Form\Test;

use App\Entity\Test;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EditTestType
 * @package App\Form\Test
 */
class EditTestType extends AbstractType
{
	/**
	 * @return null|string
	 */
    public function getParent() {
	    return AddTestType::class;
    }
}
