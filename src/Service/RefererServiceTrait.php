<?php
/**
 * Created by PhpStorm.
 * Audit: gkratz
 * Date: 23/05/2019
 * Time: 09:41.
 */

namespace App\Service;

/**
 * Trait RefererServiceTrait
 */
trait RefererServiceTrait
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
