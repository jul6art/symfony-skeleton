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
	 * FunctionalityManagerTrait constructor.
	 *
	 * @param FunctionalityManager $functionalityManager
	 */
	public function __construct(FunctionalityManager $functionalityManager)
	{
		$this->functionalityManager = $functionalityManager;
	}

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
	 * @return FunctionalityManagerTrait
	 */
	public function setFunctionalityManager(FunctionalityManager $functionalityManager): FunctionalityManagerTrait
	{
		$this->functionalityManager = $functionalityManager;

		return $this;
	}
}