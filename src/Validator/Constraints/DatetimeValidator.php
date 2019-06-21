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
 * Class DatetimeValidator
 * @package App\Validator\Constraints
 */
class DatetimeValidator extends ConstraintValidator {
	/**
	 * @param mixed $protocol
	 * @param Constraint $constraint
	 */
	public function validate($value, Constraint $constraint)
	{
		if (!$constraint instanceof Datetime) {
			throw new UnexpectedTypeException($constraint, Datetime::class);
		}

		// custom constraints should ignore null and empty values to allow
		// other constraints (NotBlank, NotNull, etc.) take care of that
		if (null === $value || '' === $value) {
			return;
		}

		dump($constraint, $this->context);

		if ($value instanceof \DateTime) {
			$this->context->buildViolation($constraint->message)
			              ->addViolation();
		}
	}
}