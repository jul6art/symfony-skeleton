<?php
/**
 * Created by PhpStorm.
 * Audit: gkratz
 * Date: 23/05/2019
 * Time: 09:41.
 */

namespace App\Manager;

/**
 * Trait AuditManagerTrait.
 */
trait AuditManagerTrait
{
    /**
     * @var AuditManager
     */
    private $userManager;

    /**
     * @return AuditManager
     */
    public function getAuditManager(): AuditManager
    {
        return $this->userManager;
    }

    /**
     * @param AuditManager $userManager
     *
     * @required
     *
     * @return AuditManagerTrait
     */
    public function setAuditManager(AuditManager $userManager): void
    {
        $this->userManager = $userManager;
    }
}
