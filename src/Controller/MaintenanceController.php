<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Controller;

use App\Event\MaintenanceEvent;
use App\Form\Maintenance\EditMaintenanceType;
use App\Manager\Traits\MaintenanceManagerAwareTrait;
use App\Security\Voter\DefaultVoter;
use App\Security\Voter\MaintenanceVoter;
use App\Service\Traits\RefererServiceAwareTrait;
use App\Transformer\MaintenanceTransformer;
use DH\DoctrineAuditBundle\Helper\AuditHelper;
use Doctrine\ORM\NonUniqueResultException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("", name="admin_maintenance_")
 */
class MaintenanceController extends AbstractFOSRestController
{
    use MaintenanceManagerAwareTrait;
    use RefererServiceAwareTrait;

    /**
     * @Route("%admin_route_prefix%/maintenance/", name="overview", methods={"GET"})
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     * @throws ExceptionInterface
     */
    public function index(Request $request, MaintenanceTransformer $maintenanceTransformer, EventDispatcherInterface $eventDispatcher): Response
    {
        $maintenance = $this->maintenanceManager->findOneByLatest();
        $this->denyAccessUnlessGranted(MaintenanceVoter::EDIT, $maintenance);

        $serializer = new Serializer([$maintenanceTransformer]);

        $view = $this->view()
                     ->setTemplate('maintenance/overview.html.twig')
                     ->setTemplateData([
                         'entity' => $maintenance,
                         'maintenance' => $serializer->normalize($maintenance, 'json'),
                     ]);

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @param MaintenanceTransformer $maintenanceTransformer
     * @param EventDispatcherInterface $eventDispatcher
     * @param AuditHelper $auditHelper
     *
     * @Route("%admin_route_prefix%/maintenance/edit", name="edit", methods={"GET", "POST"})
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     * @throws ExceptionInterface
     */
    public function edit(Request $request, MaintenanceTransformer $maintenanceTransformer, EventDispatcherInterface $eventDispatcher, AuditHelper $auditHelper): Response
    {
        $maintenance = $this->maintenanceManager->findOneByLatest();
        $this->denyAccessUnlessGranted(MaintenanceVoter::EDIT, $maintenance);

        $referer = $this->refererService->getFormReferer($request, 'maintenance');

        $form = $this->createForm(EditMaintenanceType::class, $maintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->maintenanceManager->save($maintenance);
            $eventDispatcher->dispatch(new MaintenanceEvent($maintenance), MaintenanceEvent::EDITED);

            return $this->redirect($referer ?? $this->generateUrl('admin_maintenance_overview'));
        }

        $serializer = new Serializer([$maintenanceTransformer]);

        $ip = $auditHelper->blame()['client_ip'];

        $view = $this->view()
                     ->setTemplate('maintenance/edit.html.twig')
                     ->setTemplateData([
                         'entity' => $maintenance,
                         'maintenance' => $serializer->normalize($maintenance, 'json'),
                         'form' => $form->createView(),
                         'referer' => $referer,
                         'ip' => $ip,
                     ]);

        return $this->handleView($view);
    }

    /**
     * @Route("maintenance/view", name="view", methods={"GET"})
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     */
    public function show(EventDispatcherInterface $eventDispatcher): Response
    {
        $maintenance = $this->maintenanceManager->findOneByLatest();
        $this->denyAccessUnlessGranted(DefaultVoter::MAINTENANCE);

        $eventDispatcher->dispatch(new MaintenanceEvent($maintenance), MaintenanceEvent::VIEWED);

        $view = $this->view()
                     ->setTemplate('maintenance/view.html.twig');

        return $this->handleView($view);
    }
}
