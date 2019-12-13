<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
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
