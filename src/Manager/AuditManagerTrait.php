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
    protected $auditManager;

    /**
     * @return AuditManager
     */
    public function getAuditManager(): AuditManager
    {
        return $this->auditManager;
    }

    /**
     * @param AuditManager $auditManager
     *
     * @required
     */
    public function setAuditManager(AuditManager $auditManager): void
    {
        $this->auditManager = $auditManager;
    }
}
