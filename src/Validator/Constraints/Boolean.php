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
 * Class Boolean.
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
class Boolean extends Constraint
{
    public $message = 'form.boolean.error';
}
