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
 * Class Checkbox
 * @package App\Validator\Constraints
 */
class Checkbox extends Constraint {
	public $message = 'form.checkbox.error';
}