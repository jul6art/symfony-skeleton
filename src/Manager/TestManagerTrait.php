<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/05/2019
 * Time: 09:41
 */

namespace App\Manager;

/**
 * Trait TestManagerTrait
 * @package App\Manager
 */
trait TestManagerTrait {
	/**
	 * @var TestManager
	 */
	private $testManager;

	/**
	 * @return TestManager
	 */
	public function getTestManager(): TestManager
	{
		return $this->testManager;
	}

	/**
	 * @param TestManager $testManager
	 *
	 * @required
	 */
	public function setTestManager( TestManager $testManager ): void
	{
		$this->testManager = $testManager;
	}
}