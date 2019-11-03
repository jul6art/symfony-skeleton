<?php

namespace App\Controller;

use App\Entity\Functionality;
use App\Entity\Setting;
use App\Manager\FunctionalityManagerTrait;
use App\Manager\SettingManagerTrait;
use App\Manager\TestManagerTrait;
use App\Manager\TranslationManagerTrait;
use App\Manager\UserManagerTrait;
use App\Security\Voter\DefaultVoter;
use App\Security\Voter\FunctionalityVoter;
use App\Security\Voter\SettingVoter;
use App\Service\FileServiceTrait;
use App\Service\RefererServiceTrait;
use DH\DoctrineAuditBundle\Helper\AuditHelper;
use DH\DoctrineAuditBundle\Reader\AuditReader;
use Doctrine\ORM\NonUniqueResultException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Lexik\Bundle\TranslationBundle\Entity\Translation;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class DefaultController.
 */
class DefaultController extends AbstractFOSRestController
{
    use FileServiceTrait;
    use FunctionalityManagerTrait;
    use RefererServiceTrait;
    use SettingManagerTrait;
    use TestManagerTrait;
    use TranslationManagerTrait;
    use UserManagerTrait;

    /**
     * @Route("%admin_route_prefix%/", name="admin_homepage", methods={"GET"})
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted(DefaultVoter::ACCESS_PAGE_HOME);

        $view = $this->view()
                     ->setTemplate('default/dashboard.html.twig')
                     ->setTemplateData([
                         'count_functionalities' => $this->functionalityManager->countAllByConfigured(),
                         'count_users' => $this->userManager->countAll(),
                         'count_tests' => $this->testManager->countAll(),
                     ]);

        return $this->handleView($view);
    }

    /**
     * @param Request             $request
     * @param string              $locale
     * @param TranslatorInterface $translator
     *
     * @Route("/locale/{locale}", name="locale_switch", methods={"GET"})
     *
     * @return Response
     */
    public function locale(Request $request, string $locale, TranslatorInterface $translator, array $available_locales): Response
    {
        if (!\in_array($locale, $available_locales)) {
            throw new NotFoundHttpException();
        }

        $this->denyAccessUnlessGranted(FunctionalityVoter::SWITCH_LOCALE, Functionality::class);

        $referer = $this->refererService->getFormReferer($request, 'locale');

        if (null !== $this->getUser()) {
            $this->userManager
                ->updateLocale($this->getUser(), $locale)
                ->save($this->getUser());
        }

        $session = $request->getSession();
        $session->set('_locale', $locale);
        $session->save();
        $request->setLocale($locale);
        $translator->setLocale($locale);

        return $this->redirect($referer ?? $this->generateUrl('admin_homepage'));
    }

    /**
     * @param Request             $request
     * @param KernelInterface     $kernel
     * @param TranslatorInterface $translator
     *
     * @Route("%admin_route_prefix%/cache", name="admin_cache", methods={"GET"})
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function cache(Request $request, KernelInterface $kernel, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted(FunctionalityVoter::CACHE_CLEAR, Functionality::class);

        $size = $this->fileService->getSizeAndUnit($this->getParameter('kernel.cache_dir'));

        $referer = $this->refererService->getFormReferer($request, 'cache');

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
     * @param Request $request
     * @param string  $name
     * @param array   $available_colors
     *
     * @Route("%admin_route_prefix%/theme/{name}", name="admin_theme_switch", methods={"GET"})
     *
     * @return Response
     */
    public function theme(Request $request, string $name, array $available_colors): Response
    {
        $this->denyAccessUnlessGranted(FunctionalityVoter::SWITCH_THEME, Functionality::class);

        $referer = $this->refererService->getFormReferer($request, 'theme');

        if (\in_array($name, $available_colors) and null !== $this->getUser()) {
            $this->userManager
                ->updateTheme($this->getUser(), $name)
                ->save($this->getUser());
        }

        return $this->redirect($referer ?? $this->generateUrl('admin_homepage'));
    }

    /**
     * @param Request       $request
     * @param Functionality $functionality
     * @param int           $state
     *
     * @Route("%admin_route_prefix%/functionality/{functionality}/{state}", name="admin_functionality_switch", methods={"GET"}, requirements={"state": "0|1"}, options={"expose"=true})
     *
     * @return Response
     */
    public function functionality(Request $request, Functionality $functionality, int $state = 0): Response
    {
        $this->denyAccessUnlessGranted(FunctionalityVoter::EDIT, $functionality);

        $referer = $this->refererService->getFormReferer($request, 'functionality');

        $this->functionalityManager
            ->update($functionality, (bool) $state)
            ->save($functionality);

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
     * @param string  $value
     *
     * @Route("%admin_route_prefix%/setting/{setting}/{value}", name="admin_setting_set", methods={"GET"}, options={"expose"=true})
     *
     * @return Response
     */
    public function setting(Request $request, Setting $setting, string $value = ''): Response
    {
        $this->denyAccessUnlessGranted(SettingVoter::EDIT, $setting);

        $referer = $this->refererService->getFormReferer($request, 'setting');

        $this->settingManager
            ->update($setting, $value)
            ->save($setting);

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
     * @param string  $value
     *
     * @Route("%admin_route_prefix%/translation/edit/{domain}/{key}", name="admin_translation_edit", methods={"POST"}, options={"expose"=true})
     *
     * @return Response
     */
    public function translate(Request $request, string $domain, string $key): Response
    {
        $this->denyAccessUnlessGranted(FunctionalityVoter::EDIT_IN_PLACE, Functionality::class);

        $referer = $this->refererService->getFormReferer($request, 'translate');

        $translations = $this->translationManager->findByDomainAndKeyAndLocale($domain, $key, $request->getLocale());

        $this->translationManager
            ->updateMultiple($translations, $request->request->get('value'))
            ->flush();

        $cacheDir = dirname($this->getParameter('kernel.cache_dir'));
        foreach (['prod', 'dev'] as $env) {
            array_map('unlink', glob("$cacheDir/$env/translations/*"));
            array_map('unlink', glob("$cacheDir/$env/app*ProjectContainer.php"));
        }

        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'success' => true,
            ]);
        }

        return $this->redirect($referer ?? $this->generateUrl('admin_homepage'));
    }

    /**
     * @param $class
     * @param AuditReader $auditReader
     * @param null        $id
     * @param array       $exclude
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     */
    public function audit($class, AuditReader $auditReader, $id = null, array $exclude = []): Response
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
}
