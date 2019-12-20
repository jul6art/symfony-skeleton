<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Manager\Traits;

use App\Manager\FunctionalityManager;

/**
 * Trait FunctionalityManagerAwareTrait.
 */
trait FunctionalityManagerAwareTrait
{
    /**
     * @var FunctionalityManager
     */
    protected $functionalityManager;

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
