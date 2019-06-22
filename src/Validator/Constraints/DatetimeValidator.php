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
 * Class DatetimeValidator.
 */
class DatetimeValidator extends ConstraintValidator
{
    /**
     * @param mixed      $protocol
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

        if (!$value instanceof \DateTime) {
            $this->context->buildViolation($constraint->message)
                          ->addViolation();
        } else {
            $options = $this->context->getObject()->getConfig()->getAttributes()['data_collector/passed_options'];

            if (key_exists('minDate', $options) && $value->format('Y-m-d') < (new \DateTime($options['minDate']))->format('Y-m-d')) {
                $this->context->buildViolation($constraint->message_min_date)
                              ->setParameter('{{ date }}', $options['minDate'])
                              ->addViolation();
            } elseif (key_exists('maxDate', $options) && $value->format('Y-m-d') > (new \DateTime($options['maxDate']))->format('Y-m-d')) {
                $this->context->buildViolation($constraint->message_max_date)
                              ->setParameter('{{ date }}', $options['maxDate'])
                              ->addViolation();
            } elseif (key_exists('disabledDays', $options) && in_array($value->format('w'), json_decode($options['disabledDays']))) {
                $this->context->buildViolation($constraint->message_disabled_days)
                              ->addViolation();
            }
        }
    }
}
