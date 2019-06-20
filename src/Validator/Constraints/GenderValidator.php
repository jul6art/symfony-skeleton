<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 11/06/2019
 * Time: 09:34
 */

namespace App\Validator\Constraints;

use App\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class GenderValidator
 * @package App\Validator\Constraints
 */
class GenderValidator extends ConstraintValidator {
	/**
	 * @param mixed $protocol
	 * @param Constraint $constraint
	 */
	public function validate($value, Constraint $constraint)
	{
		if (!$constraint instanceof Gender) {
			throw new UnexpectedTypeException($constraint, Gender::class);
		}

		// custom constraints should ignore null and empty values to allow
		// other constraints (NotBlank, NotNull, etc.) take care of that
		if (null === $value || '' === $value) {
			return;
		}

		if (!in_array($value, [User::GENDER_MALE, User::GENDER_FEMALE])) {
			$this->context->buildViolation($constraint->message)
			              ->addViolation();
		}
	}
}