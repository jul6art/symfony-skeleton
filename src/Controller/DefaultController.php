<?php

namespace App\Controller;

use App\Entity\Functionality;
use App\Manager\UserManagerTrait;
use App\Security\Voter\FunctionalityVoter;
use App\Service\RefererService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class DefaultController extends AbstractFOSRestController
{
	use UserManagerTrait;

    /**
     * @Route("/", name="homepage", methods={"GET"}, options={"expose"=true})
     */
    public function index(): Response
    {
    	die ('hello world');
    }

	/**
	 * @param Request $request
	 * @param string $name
	 * @param array $available_colors
	 * @param RefererService $refererService
	 *
	 * @Route("/theme/{name}", name="theme_switch", methods={"GET"})
	 *
	 * @return Response
	 */
    public function theme(Request $request, string $name, array $available_colors, RefererService $refererService): Response
    {
    	$this->denyAccessUnlessGranted(FunctionalityVoter::SWITCH_THEME, Functionality::class);

    	$referer = $refererService->getFormReferer($request, 'theme');

		if (in_array($name, $available_colors)) {
			$user = ($this->getUser())
				->setTheme($name);

			$this->userManager->save($user);
		}

    	if (!is_null($referer)) {
    		return $this->redirect($referer);
	    }

        return $this->redirectToRoute('admin_homepage');
    }

	/**
	 * @param Request $request
	 * @param string $locale
	 * @param RefererService $refererService
	 *
	 * @Route("/locale/{locale}", name="locale_switch", methods={"GET"}, requirements={"locale": "%available_locales%"})
	 *
	 * @return Response
	 */
    public function locale(Request $request, string $locale, RefererService $refererService): Response
    {
    	$this->denyAccessUnlessGranted(FunctionalityVoter::SWITCH_LOCALE, Functionality::class);

    	$referer = $refererService->getFormReferer($request, 'locale');

    	$user = $this->getUser();

		$user->setLocale($locale);

    	$this->userManager->save($user);

    	if (!is_null($referer)) {
    		return $this->redirect($referer);
	    }

        return $this->redirectToRoute('admin_homepage');
    }
}
