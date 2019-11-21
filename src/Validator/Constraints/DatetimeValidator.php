<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Validator\Constraints;

use DateTime as BaseDateTime;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class DatetimeValidator.
 */
class DatetimeValidator extends ConstraintValidator
{
    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Datetime) {
            throw new UnexpectedTypeException($constraint, Datetime::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value or '' === $value) {
            return;
        }

        if (!$value instanceof BaseDateTime) {
            $this->context->buildViolation($constraint->message)
                          ->addViolation();
        } else {
            $options = $this->context->getObject()->getConfig()->getAttributes()['data_collector/passed_options'];

            if (array_key_exists('minDate', $options) and $value->format('Y-m-d') < (new BaseDateTime($options['minDate']))->format('Y-m-d')) {
                $this->context->buildViolation($constraint->message_min_date)
                              ->setParameter('{{ date }}', $options['minDate'])
                              ->addViolation();
            } elseif (array_key_exists('maxDate', $options) and $value->format('Y-m-d') > (new BaseDateTime($options['maxDate']))->format('Y-m-d')) {
                $this->context->buildViolation($constraint->message_max_date)
                              ->setParameter('{{ date }}', $options['maxDate'])
                              ->addViolation();
            } elseif (array_key_exists('disabledDays', $options) and \in_array($value->format('w'), json_decode($options['disabledDays']))) {
                $this->context->buildViolation($constraint->message_disabled_days)
                              ->addViolation();
            }
        }
    }
}
