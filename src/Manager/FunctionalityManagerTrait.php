<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/05/2019
 * Time: 09:41
 */

namespace App\Manager;

/**
 * Trait FunctionalityManagerTrait
 * @package App\Manager
 */
trait FunctionalityManagerTrait {
	/**
	 * @var FunctionalityManager
	 */
	private $functionalityManager;

	/**
	 * @return FunctionalityManager
	 */
	public function getFunctionalityManager(): FunctionalityManager
	{
		return $this->functionalityManager;
	}

	/**
	 * @param FunctionalityManager $functionalityManager
	 *
	 * @required
	 */
	public function setFunctionalityManager(FunctionalityManager $functionalityManager): void
	{
		$this->functionalityManager = $functionalityManager;
	}
}