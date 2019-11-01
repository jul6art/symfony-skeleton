<?php

namespace App\Controller;

use App\Security\Voter\DefaultVoter;
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
        $this->denyAccessUnlessGranted(DefaultVoter::MAINTENANCE);

        $view = $this->view()
                     ->setTemplate('default/dashboard.html.twig')
                     ->setTemplateData([
                         'count_functionalities' => '',
                     ]);

        return $this->handleView($view);
    }
}
