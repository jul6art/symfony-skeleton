<?php

namespace App\Controller;

use App\Entity\Maintenance;
use App\Event\MaintenanceEvent;
use App\Form\Maintenance\EditMaintenanceType;
use App\Manager\MaintenanceManagerTrait;
use App\Security\Voter\DefaultVoter;
use App\Security\Voter\MaintenanceVoter;
use App\Service\RefererServiceTrait;
use App\Transformer\MaintenanceTransformer;
use Doctrine\ORM\NonUniqueResultException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("", name="admin_maintenance_")
 */
class MaintenanceController extends AbstractFOSRestController
{
    use MaintenanceManagerTrait;
    use RefererServiceTrait;

    /**
     * @Route("%admin_route_prefix%/maintenance/", name="overview", methods={"GET"})
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     */
    public function index(Request $request, EventDispatcherInterface $eventDispatcher): Response
    {
        $maintenance = $this->maintenanceManager->findOneByLatest();
        $this->denyAccessUnlessGranted(MaintenanceVoter::EDIT, $maintenance);

        $view = $this->view()
                     ->setTemplate('test/edit.html.twig');

        return $this->handleView($view);
    }

    /**
     * @Route("%admin_route_prefix%/maintenance/edit", name="edit", methods={"GET", "POST"})
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     */
    public function edit(Request $request, MaintenanceTransformer $maintenanceTransformer, EventDispatcherInterface $eventDispatcher): Response
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

        $view = $this->view()
                     ->setTemplate('maintenance/edit.html.twig')
                     ->setTemplateData([
                         'entity' => $maintenance,
                         'maintenance' => $serializer->normalize($maintenance, 'json'),
                         'form' => $form->createView(),
                         'referer' => $referer,
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
