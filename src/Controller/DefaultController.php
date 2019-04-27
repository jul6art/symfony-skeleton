<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\UserManager;
use App\Security\Voter\DefaultVoter;
use App\Service\RefererService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage", methods={"GET"})
     */
    public function index(): Response
    {
    	die ('hello world');
    }

    /**
     * @Route("/theme/{name}", name="theme_switch", methods={"GET"})
     */
    public function theme(Request $request, string $name, UserManager $userManager, RefererService $refererService): Response
    {
    	$this->denyAccessUnlessGranted(DefaultVoter::SWITCH_THEME, User::class);

    	$referer = $refererService->getFormReferer($request, 'theme');

    	$user = $this->getUser();

		$user->setTheme($name);

    	$userManager->save($user);

    	if (!is_null($referer)) {
    		return $this->redirect($referer);
	    }

        return $this->redirectToRoute('admin_homepage');
    }
}
