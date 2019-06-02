<?php

namespace App\Controller;

use App\Entity\Functionality;
use App\Manager\FunctionalityManagerTrait;
use App\Manager\UserManagerTrait;
use App\Security\Voter\FunctionalityVoter;
use App\Service\FileService;
use App\Service\RefererService;
use DH\DoctrineAuditBundle\Helper\AuditHelper;
use DH\DoctrineAuditBundle\Reader\AuditReader;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/admin", name="admin_")
 */
class DefaultController extends AbstractFOSRestController
{
    use UserManagerTrait;
    use FunctionalityManagerTrait;

    /**
     * @Route("/", name="homepage", methods={"GET"})
     */
    public function index(): Response
    {
        die('hello world');
    }

    /**
     * @param Request        $request
     * @param RefererService $refererService
     *
     * @Route("/cache", name="cache", methods={"GET"})
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function cache(Request $request, RefererService $refererService, FileService $fileService, KernelInterface $kernel, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted(FunctionalityVoter::CACHE_CLEAR, Functionality::class);

        $size = $fileService->getSizeAndUnit($this->getParameter('kernel.cache_dir'));

        $referer = $refererService->getFormReferer($request, 'cache');

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'cache:clear',
            '--env' => $kernel->getEnvironment(),
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $this->addFlash('success', $translator->trans('notification.cache.cleared', ['%size%' => $size], 'notification'));

        if (!is_null($referer)) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('admin_homepage');
    }

    /**
     * @param Request        $request
     * @param string         $name
     * @param array          $available_colors
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
     * @param Request        $request
     * @param string         $locale
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

    /**
     * @param $class
     * @param null        $id
     * @param AuditReader $auditReader
     * @param int         $audit_limit
     *
     * @return Response
     */
    public function audit($class, $id = null, AuditReader $auditReader, int $audit_limit): Response
    {
        $auditEntity = AuditHelper::paramToNamespace($class);
        $audits = $auditReader->getAudits($auditEntity, $id, 1, $audit_limit);

        $view = $this->view()
                     ->setTemplate('includes/audit.html.twig')
                     ->setTemplateData([
                         'audits' => $audits,
                         'users' => $this->userManager->findAllForAudit(),
                     ]);

        return $this->handleView($view);
    }

	/**
	 * @param Request        $request
	 * @param string         $locale
	 * @param RefererService $refererService
	 *
	 * @Route("/functionality/{functionality}/{state}", name="functionality_switch", methods={"GET"}, requirements={"state": "0|1"}, options={"expose"=true})
	 *
	 * @return Response
	 */
	public function functionality(Request $request, Functionality $functionality, int $state = 0): Response
	{
		$this->denyAccessUnlessGranted(FunctionalityVoter::MANAGE_SETTINGS, Functionality::class);

		if ($request->isXmlHttpRequest()) {
			$this->functionalityManager->update($functionality, (bool) $state);

			$this->functionalityManager->save($functionality);

			return $this->json([
				'success' => true,
			]);
		}

		return $this->redirectToRoute('admin_homepage');
	}
}
