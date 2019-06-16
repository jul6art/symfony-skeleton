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
 * Class RangeValidator
 * @package App\Validator\Constraints
 */
class RangeValidator extends ConstraintValidator {
	/**
	 * @param mixed $protocol
	 * @param Constraint $constraint
	 */
	public function validate($value, Constraint $constraint)
	{
		if (!$constraint instanceof Range) {
			throw new UnexpectedTypeException($constraint, Range::class);
		}

		// custom constraints should ignore null and empty values to allow
		// other constraints (NotBlank, NotNull, etc.) take care of that
		if (null === $value || '' === $value) {
			return;
		}

		$options = $this->context->getObject()->getConfig()->getAttributes()['data_collector/passed_options'];

		if ($value < $options['min']
		    || $value > $options['max']
		    || (($value != $options['max']) && (($value - $options['min']) %$options['step'] != 0))) {
			$this->context->buildViolation($constraint->message)
			              ->addViolation();
		}
	}
}