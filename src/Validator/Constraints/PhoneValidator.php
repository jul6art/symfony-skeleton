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
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class PhoneValidator
 * @package App\Validator\Constraints
 */
class PhoneValidator extends ConstraintValidator {
	/**
	 * @param mixed $protocol
	 * @param Constraint $constraint
	 */
	public function validate($value, Constraint $constraint)
	{
		if (!$constraint instanceof Phone) {
			throw new UnexpectedTypeException($constraint, Phone::class);
		}

		// custom constraints should ignore null and empty values to allow
		// other constraints (NotBlank, NotNull, etc.) take care of that
		if (null === $value || '' === $value) {
			return;
		}

		if (substr($value, 0, 1) !== '+') {
			$this->context->buildViolation($constraint->message)
			              ->addViolation();
		}
	}
}