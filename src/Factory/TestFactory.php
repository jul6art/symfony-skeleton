<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/05/2019
 * Time: 09:25
 */

namespace App\Factory;

use App\Entity\Test;

/**
 * Class TestFactory
 * @package App\Factory
 */
class TestFactory {
	/**
	 * @return Test
	 */
	public static function create(): Test
	{
		return new Test();
	}
}