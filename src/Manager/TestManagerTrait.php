<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Manager;

/**
 * Trait TestManagerTrait.
 */
trait TestManagerTrait
{
    /**
     * @var TestManager
     */
    protected $testManager;

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
    public function setTestManager(TestManager $testManager): void
    {
        $this->testManager = $testManager;
    }
}
