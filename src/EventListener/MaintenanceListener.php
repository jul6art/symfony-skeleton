<?php

/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/07/2019
 * Time: 13:35.
 */

namespace App\EventListener;

use App\Manager\MaintenanceManagerTrait;
use App\Security\Voter\DefaultVoter;
use DH\DoctrineAuditBundle\Helper\AuditHelper;
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
    use MaintenanceManagerTrait;

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
        $isRouteMaintenance = $request->attributes->get('_route') === $routeMaintenance;

        if ($this->authorizationChecker->isGranted(DefaultVoter::MAINTENANCE) and !$isRouteMaintenance) {
            $blame = $this->helper->blame();

            $maintenance = $this->maintenanceManager->findOneByLatest();

            if (null !== $maintenance and !\in_array($blame['client_ip'], $maintenance->getExceptionIpList())) {
                $redirect = $routeMaintenance;
            }
        } elseif (!$this->authorizationChecker->isGranted(DefaultVoter::MAINTENANCE) and $isRouteMaintenance) {
            $redirect = 'admin_homepage';
        }

        if ($redirect) {
            $event->setResponse(new RedirectResponse($this->router->generate($redirect)));
        }
    }
}
