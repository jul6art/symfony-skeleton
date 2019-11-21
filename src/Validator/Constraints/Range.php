<?php

/**
 * Created by PhpStorm.
 * User: Jul6art
 * Date: 11/06/2019
 * Time: 09:34.
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Range.
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
class Range extends Constraint
{
    public $message = 'form.range.error';
}
