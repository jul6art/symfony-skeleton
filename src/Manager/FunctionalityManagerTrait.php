<?php

/**
 * Created by PhpStorm.
 * User: Jul6art
 * Date: 23/05/2019
 * Time: 09:41.
 */

namespace App\Manager;

/**
 * Trait FunctionalityManagerTrait.
 */
trait FunctionalityManagerTrait
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
