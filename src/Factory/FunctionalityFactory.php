<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/05/2019
 * Time: 09:25
 */

namespace App\Factory;

use App\Entity\Functionality;

/**
 * Class FunctionalityFactory
 * @package App\Factory
 */
class FunctionalityFactory {
	/**
	 * @return Functionality
	 */
	public static function create(): Functionality
	{
		return new Functionality();
	}
}