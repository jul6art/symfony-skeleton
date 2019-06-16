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
 * Class Phone
 * @package App\Validator\Constraints
 */
class Phone extends Constraint {
	public $message = 'form.phone.error';
}