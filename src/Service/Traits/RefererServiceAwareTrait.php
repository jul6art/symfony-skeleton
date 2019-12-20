<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Service\Traits;

use App\Service\RefererService;

/**
 * Trait RefererServiceAwareTrait.
 */
trait RefererServiceAwareTrait
{
    /**
     * @var RefererService
     */
    private $refererService;

    /**
     * @return RefererService
     */
    public function getRefererService(): RefererService
    {
        return $this->refererService;
    }

    /**
     * @param RefererService $refererService
     *
     * @required
     */
    public function setRefererService(RefererService $refererService): void
    {
        $this->refererService = $refererService;
    }
}
