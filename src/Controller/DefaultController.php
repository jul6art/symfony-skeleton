<?php

namespace App\Controller;

use App\Entity\Functionality;
use App\Manager\UserManager;
use App\Security\Voter\FunctionalityVoter;
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
    public function theme(Request $request, string $name, array $available_colors, UserManager $userManager, RefererService $refererService): Response
    {
    	$this->denyAccessUnlessGranted(FunctionalityVoter::SWITCH_THEME, Functionality::class);

    	$referer = $refererService->getFormReferer($request, 'theme');

		if (in_array($name, $available_colors)) {
			$user = ($this->getUser())
				->setTheme($name);

			$userManager->save($user);
		}

    	if (!is_null($referer)) {
    		return $this->redirect($referer);
	    }

        return $this->redirectToRoute('admin_homepage');
    }

    /**
     * @Route("/locale/{locale}", name="locale_switch", methods={"GET"}, requirements={"locale": "%available_locales%"})
     */
    public function locale(Request $request, string $locale, UserManager $userManager, RefererService $refererService): Response
    {
    	$this->denyAccessUnlessGranted(FunctionalityVoter::SWITCH_LOCALE, Functionality::class);

    	$referer = $refererService->getFormReferer($request, 'locale');

    	$user = $this->getUser();

		$user->setLocale($locale);

    	$userManager->save($user);

    	if (!is_null($referer)) {
    		return $this->redirect($referer);
	    }

        return $this->redirectToRoute('admin_homepage');
    }
}
