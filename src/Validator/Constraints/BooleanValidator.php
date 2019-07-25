<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 11/06/2019
 * Time: 09:34.
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class BooleanValidator.
 */
class BooleanValidator extends ConstraintValidator
{
	/**
	 * @param mixed $value
	 * @param Constraint $constraint
	 */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Boolean) {
            throw new UnexpectedTypeException($constraint, Boolean::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value or '' === $value) {
            return;
        }

        if (\in_array($value, [0, 1])) {
            $value = (bool) $value;
        }

        if (!\is_bool($value)) {
            $this->context->buildViolation($constraint->message)
                          ->addViolation();
        }
    }
}
