<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Validator\Constraints;

use App\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class GenderValidator.
 */
class GenderValidator extends ConstraintValidator
{
    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Gender) {
            throw new UnexpectedTypeException($constraint, Gender::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value or '' === $value) {
            return;
        }

        if (!\in_array($value, [User::GENDER_MALE, User::GENDER_FEMALE])) {
            $this->context->buildViolation($constraint->message)
                          ->addViolation();
        }
    }
}
