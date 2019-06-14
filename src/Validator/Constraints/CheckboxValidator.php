<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 11/06/2019
 * Time: 09:34
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class CheckboxValidator
 * @package App\Validator\Constraints
 */
class CheckboxValidator extends ConstraintValidator {
	/**
	 * @param mixed $protocol
	 * @param Constraint $constraint
	 */
	public function validate($value, Constraint $constraint)
	{
		if (is_bool($value)) {
			$this->context->buildViolation($constraint->message)
			              ->addViolation();
		}
	}
}