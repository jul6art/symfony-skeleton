<?php

namespace App\Controller;

use App\Entity\Maintenance;
use App\Manager\MaintenanceManagerTrait;
use App\Security\Voter\MaintenanceVoter;
use App\Service\RefererServiceTrait;
use Doctrine\ORM\NonUniqueResultException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("%admin_route_prefix%/maintenance", name="admin_maintenance_")
 */
class MaintenanceController extends AbstractFOSRestController
{
    use MaintenanceManagerTrait;
    use RefererServiceTrait;

    /**
     * @Route("/", name="overview", methods={"GET"})
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     */
    public function index(): Response
    {
        $maintenance = $this->maintenanceManager->findOneByLatest();
        $this->denyAccessUnlessGranted(MaintenanceVoter::EDIT, $maintenance);

        $view = $this->view()
                     ->setTemplate('default/dashboard.html.twig')
                     ->setTemplateData([
                         'count_functionalities' => '',
                     ]);

        return $this->handleView($view);
    }
}
