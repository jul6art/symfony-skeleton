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
	 * TestManagerTrait constructor.
	 *
	 * @param TestManager $testManager
	 */
	public function __construct(TestManager $testManager)
	{
		$this->testManager = $testManager;
	}

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
	 * @return TestManagerTrait
	 */
	public function setTestManager( TestManager $testManager ): TestManagerTrait
	{
		$this->testManager = $testManager;

		return $this;
	}
}