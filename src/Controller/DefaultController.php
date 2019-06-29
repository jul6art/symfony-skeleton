<?php

namespace App\Controller;

use App\Entity\Functionality;
use App\Entity\Setting;
use App\Manager\FunctionalityManagerTrait;
use App\Manager\SettingManagerTrait;
use App\Manager\UserManagerTrait;
use App\Security\Voter\FunctionalityVoter;
use App\Security\Voter\SettingVoter;
use App\Service\FileService;
use App\Service\RefererService;
use DH\DoctrineAuditBundle\Helper\AuditHelper;
use DH\DoctrineAuditBundle\Reader\AuditReader;
use Doctrine\ORM\NonUniqueResultException;
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
    use SettingManagerTrait;

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
        $application->run(new ArrayInput([
            'command' => 'cache:clear',
            '--env' => $kernel->getEnvironment(),
        ]), new BufferedOutput());

        $this->addFlash('success', $translator->trans('notification.cache.cleared', ['%size%' => $size], 'notification'));

        return $this->redirect($referer ?? $this->generateUrl('admin_homepage'));
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

        if (\in_array($name, $available_colors)) {
            $this->userManager->updateTheme($this->getUser(), $name);
        }

	    return $this->redirect($referer ?? $this->generateUrl('admin_homepage'));
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

        $this->userManager->updateLocale($this->getUser(), $locale);

	    return $this->redirect($referer ?? $this->generateUrl('admin_homepage'));
    }

    /**
     * @param $class
     * @param null        $id
     * @param AuditReader $auditReader
     * @param array       $exclude
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     */
    public function audit($class, $id = null, AuditReader $auditReader, array $exclude = []): Response
    {
        $view = $this->view()
                     ->setTemplate('includes/audit.html.twig')
                     ->setTemplateData([
                         'audits' => $auditReader->getAudits(
                             AuditHelper::paramToNamespace($class),
                             $id,
                             1,
                             $this->settingManager->findOneValueByName(Setting::SETTING_AUDIT_LIMIT, Setting::SETTING_AUDIT_LIMIT_VALUE)
                         ),
                         'users' => $this->userManager->findAllForAudit(),
                         'exclude' => $exclude,
                     ]);

        return $this->handleView($view);
    }

	/**
	 * @param Request $request
	 * @param Functionality $functionality
	 * @param int $state
	 * @param RefererService $refererService
	 *
	 * @Route("/functionality/{functionality}/{state}", name="functionality_switch", methods={"GET"}, requirements={"state": "0|1"}, options={"expose"=true})
	 *
	 * @return Response
	 */
    public function functionality(Request $request, Functionality $functionality, int $state = 0, RefererService $refererService): Response
    {
        $this->denyAccessUnlessGranted(FunctionalityVoter::EDIT, $functionality);

        $referer = $refererService->getFormReferer($request, 'functionality');

        $this->functionalityManager->updateState($functionality, (bool) $state);

        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'success' => true,
            ]);
        }

	    return $this->redirect($referer ?? $this->generateUrl('admin_homepage'));
    }

	/**
	 * @param Request $request
	 * @param Setting $setting
	 * @param string $value
	 * @param RefererService $refererService
	 *
	 * @Route("/setting/{setting}/{value}", name="setting_set", methods={"GET"}, options={"expose"=true})
	 *
	 * @return Response
	 */
    public function setting(Request $request, Setting $setting, string $value = '', RefererService $refererService): Response
    {
        $this->denyAccessUnlessGranted(SettingVoter::EDIT, $setting);

        $referer = $refererService->getFormReferer($request, 'setting');

        $this->settingManager->update($setting, $value);

        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'success' => true,
            ]);
        }

	    return $this->redirect($referer ?? $this->generateUrl('admin_homepage'));
    }
}
