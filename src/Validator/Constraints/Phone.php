<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Phone.
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
class Phone extends Constraint
{
    public $message = 'form.phone.error';
}
