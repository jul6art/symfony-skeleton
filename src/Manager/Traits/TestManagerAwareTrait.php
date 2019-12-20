<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Manager\Traits;

use App\Manager\TestManager;

/**
 * Trait TestManagerAwareTrait.
 */
trait TestManagerAwareTrait
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
