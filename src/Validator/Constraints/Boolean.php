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
 * Class Boolean
 * @package App\Validator\Constraints
 */
class Boolean extends Constraint {
	public $message = 'form.boolean.error';
}