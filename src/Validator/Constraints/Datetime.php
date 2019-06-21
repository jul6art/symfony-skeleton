<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 11/06/2019
 * Time: 09:34
 */

namespace App\Validator\Constraints;
use Symfony\Component\Validator\Constraint;

/**
 * Class Datetime
 * @package App\Validator\Constraints
 */
class Datetime extends Constraint {
	public $message = 'form.datetime.error';
	public $message_min_date = 'form.datetime.error_min_date';
	public $message_max_date = 'form.datetime.error_max_date';
	public $message_disabled_days = 'form.datetime.error_disabled_days';
}