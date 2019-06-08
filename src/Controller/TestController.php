<?php

namespace App\Controller;

use App\Entity\Test;
use App\Event\TestEvent;
use App\Form\Test\AddTestType;
use App\Form\Test\EditTestType;
use App\Manager\GroupManagerTrait;
use App\Manager\TestManagerTrait;
use App\Manager\UserManagerTrait;
use App\Security\Voter\TestVoter;
use App\Service\RefererService;
use App\Transformer\TestDataTableTransformer;
use App\Transformer\TestTransformer;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/admin/test", name="admin_")
 */
class TestController extends AbstractFOSRestController
{
    use TestManagerTrait;
    use UserManagerTrait;

    /**
     * @param TestDataTableTransformer $testDataTableTransformer
     *
     * @Route("/", name="test_list", methods={"GET"})
     *
     * @return Response
     *
     * @throws ExceptionInterface
     */
    public function index(TestDataTableTransformer $testDataTableTransformer): Response
    {
        $this->denyAccessUnlessGranted(TestVoter::LIST, Test::class);

        $serializer = new Serializer([$testDataTableTransformer]);

        $view = $this->view()
                     ->setTemplate('test/list.html.twig')
                     ->setTemplateData([
                         'tests' => $serializer->normalize($this->testManager->findAllForTable(), 'json', [
                         ]),
                     ]);

        return $this->handleView($view);
    }

    /**
     * @param Request         $request
     * @param TestTransformer $testTransformer
     *
     * @Route("/add", name="test_add", methods={"GET","POST"})
     *
     * @return Response
     *
     * @throws ExceptionInterface
     */
    public function add(Request $request, TestTransformer $testTransformer, EventDispatcherInterface $eventDispatcher): Response
    {
        $this->denyAccessUnlessGranted(TestVoter::ADD, Test::class);

        $test = $this->testManager->create();

        $form = $this->createForm(AddTestType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->testManager->save($test);
            $eventDispatcher->dispatch(TestEvent::ADDED, new TestEvent($test));

            return $this->redirectToRoute('admin_test_list');
        }

        $serializer = new Serializer([$testTransformer]);

        $view = $this->view()
                     ->setTemplate('test/add.html.twig')
                     ->setTemplateData([
                         'test' => $serializer->normalize($test, 'json'),
                         'form' => $form->createView(),
                     ]);

        return $this->handleView($view);
    }

    /**
     * @param Test            $test
     * @param TestTransformer $testTransformer
     *
     * @Route("/view/{id}", name="test_view", methods={"GET"})
     *
     * @return Response
     *
     * @throws ExceptionInterface
     */
    public function show(Test $test, TestTransformer $testTransformer): Response
    {
        $this->denyAccessUnlessGranted(TestVoter::VIEW, $test);

        $serializer = new Serializer([$testTransformer]);

        $view = $this->view()
                     ->setTemplate('test/view.html.twig')
                     ->setTemplateData([
                         'entity' => $test,
                         'test' => $serializer->normalize($test, 'json'),
                     ]);

        return $this->handleView($view);
    }

    /**
     * @param Request                  $request
     * @param Test                     $test
     * @param TestTransformer          $testTransformer
     * @param EventDispatcherInterface $eventDispatcher
     * @param RefererService           $refererService
     *
     * @Route("/edit/{id}", name="test_edit", methods={"GET","POST"})
     *
     * @return Response
     *
     * @throws ExceptionInterface
     */
    public function edit(Request $request, Test $test, TestTransformer $testTransformer, EventDispatcherInterface $eventDispatcher, RefererService $refererService): Response
    {
        $this->denyAccessUnlessGranted(TestVoter::EDIT, $test);

        $referer = $refererService->getFormReferer($request, 'test');

        $form = $this->createForm(EditTestType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->testManager->save($test);
            $eventDispatcher->dispatch(TestEvent::EDITED, new TestEvent($test));

            return !is_null($referer) ? $this->redirect($referer) : $this->redirectToRoute('admin_test_list');
        }

        $serializer = new Serializer([$testTransformer]);

        $view = $this->view()
                     ->setTemplate('test/edit.html.twig')
                     ->setTemplateData([
                         'entity' => $test,
                         'test' => $serializer->normalize($test, 'json'),
                         'form' => $form->createView(),
                         'referer' => $referer,
                     ]);

        return $this->handleView($view);
    }

    /**
     * @param Request                  $request
     * @param Test                     $test
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @Route("/delete/{id}", name="test_delete", methods={"GET"})
     *
     * @return JsonResponse|RedirectResponse
     */
    public function delete(Request $request, Test $test, EventDispatcherInterface $eventDispatcher): Response
    {
        $this->denyAccessUnlessGranted(TestVoter::DELETE, $test);

        $eventDispatcher->dispatch(TestEvent::DELETED, new TestEvent($test));
        $this->testManager->delete($test);

        return $request->isXmlHttpRequest() ? $this->json(['success' => true]) : $this->redirectToRoute('admin_test_list');
    }
}
