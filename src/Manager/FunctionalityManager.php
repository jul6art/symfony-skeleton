<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/04/2019
 * Time: 13:09
 */

namespace App\Manager;

use App\Entity\Functionality;

/**
 * Class FunctionalityManager
 * @package App\Manager
 */
class FunctionalityManager extends AbstractManager {

	/**
	 * @return Functionality
	 */
	public function create(): Functionality
	{
		return (new Functionality())
			->setActive(true);
	}
}