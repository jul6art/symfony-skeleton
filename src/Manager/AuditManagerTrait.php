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
