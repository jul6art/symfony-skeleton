<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\EventListener;

use App\Event\MaintenanceEvent;
use App\Manager\Traits\AuditManagerAwareTrait;
use App\Manager\Traits\MaintenanceManagerAwareTrait;
use App\Security\Voter\DefaultVoter;
use DH\DoctrineAuditBundle\Helper\AuditHelper;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class MaintenanceListener.
 */
class MaintenanceListener
{
    use AuditManagerAwareTrait;
    use MaintenanceManagerAwareTrait;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var AuditHelper
     */
    private $helper;

    /**
     * MaintenanceListener constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param RouterInterface               $router
     * @param AuditHelper                   $helper
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker, RouterInterface $router, AuditHelper $helper)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->router = $router;
        $this->helper = $helper;
    }

    /**
     * @param RequestEvent $event
     *
     * @throws NonUniqueResultException
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!$event->isMasterRequest()) {
            return;
        }

        $redirect = false;
        $routeMaintenance = 'admin_maintenance_view';
        $isRouteMaintenance = \in_array($request->attributes->get('_route'), [
            $routeMaintenance,
            'admin_maintenance_edit',
            'admin_maintenance_overview',
            'fos_js_routing_js',
            '_wdt',
            'web_profiler_wdt',
            'web_profiler_profiler',
            'bazinga_jstranslation_js',
        ]);

        if ($this->authorizationChecker->isGranted(DefaultVoter::MAINTENANCE) and !$isRouteMaintenance) {
            $blame = $this->helper->blame();

            $maintenance = $this->maintenanceManager->findOneByLatest();

            if (null !== $maintenance and !\in_array($blame['client_ip'], $maintenance->getExceptionIpList())) {
                $redirect = $routeMaintenance;
            }
        } elseif (
            !$this->authorizationChecker->isGranted(DefaultVoter::MAINTENANCE)
                  and $routeMaintenance === $request->attributes->get('_route')
        ) {
            $redirect = 'admin_homepage';
        }

        if ($redirect) {
            $event->setResponse(new RedirectResponse($this->router->generate($redirect)));
        }
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
