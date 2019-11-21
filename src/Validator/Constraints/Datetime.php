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
 * Class Datetime.
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
class Datetime extends Constraint
{
    public $message = 'form.datetime.error';
    public $message_min_date = 'form.datetime.error_min_date';
    public $message_max_date = 'form.datetime.error_max_date';
    public $message_disabled_days = 'form.datetime.error_disabled_days';
}
