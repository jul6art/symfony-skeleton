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
 * Class RangeValidator.
 */
class RangeValidator extends ConstraintValidator
{
	/**
	 * @param mixed $value
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

        if (!array_key_exists('double', $options) || !$options['double']) {
            if ($value < $options['min']
                || $value > $options['max']
                || (($value != $options['max']) && (0 !== ($value - $options['min']) % $options['step']))) {
                $this->context->buildViolation($constraint->message)
                              ->addViolation();
            }
        } else {
            if (2 !== \count($value)) {
                $this->context->buildViolation($constraint->message)
                              ->addViolation();
            } else {
                $min = $value[0];
                $max = end($value);
                if ($min < $options['min']
                    || $max > $options['max']
                    || (($min != $options['max']) && (0 !== ($min - $options['min']) % $options['step']))
                    || (($max != $options['max']) && (0 !== ($max - $options['min']) % $options['step']))) {
                    $this->context->buildViolation($constraint->message)
                                  ->addViolation();
                }
            }
        }
    }
}
