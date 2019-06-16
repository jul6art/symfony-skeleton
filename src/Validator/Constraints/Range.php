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
 * Class Range
 * @package App\Validator\Constraints
 */
class Range extends Constraint {
	public $message = 'form.range.error';
}