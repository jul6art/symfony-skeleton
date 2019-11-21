<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\EventSubscriber;

use App\Event\MaintenanceEvent;
use App\Manager\AuditManagerTrait;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\Mapping\MappingException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserSubscriber.
 */
class MaintenanceSubscriber implements EventSubscriberInterface
{
    use AuditManagerTrait;

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            MaintenanceEvent::VIEWED => 'onMaintenanceViewed',
        ];
    }

    /**
     * @param MaintenanceEvent $event
     *
     * @throws DBALException
     * @throws MappingException
     */
    public function onMaintenanceViewed(MaintenanceEvent $event): void
    {
        $maintenance = $event->getMaintenance();

        $this->auditManager->audit('mnt_vi', $maintenance, [
            'id' => $maintenance->getId(),
        ]);
    }
}
