<?php

/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 11/06/2019
 * Time: 09:34.
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Gender.
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
class Gender extends Constraint
{
    public $message = 'form.gender.error';
}
