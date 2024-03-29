<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Controller;

use App\Entity\Test;
use App\Event\TestEvent;
use App\Form\Test\AddTestType;
use App\Form\Test\EditTestType;
use App\Manager\Traits\TestManagerAwareTrait;
use App\Manager\Traits\UserManagerAwareTrait;
use App\Security\Voter\TestVoter;
use App\Service\Traits\RefererServiceAwareTrait;
use App\Transformer\TestDataTableTransformer;
use App\Transformer\TestTransformer;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("%admin_route_prefix%/test", name="admin_test_")
 */
class TestController extends AbstractFOSRestController
{
    use RefererServiceAwareTrait;
    use TestManagerAwareTrait;
    use UserManagerAwareTrait;

    /**
     * @param TestDataTableTransformer $testDataTableTransformer
     *
     * @Route("/", name="list", methods={"GET"})
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
                         'tests' => $serializer->normalize($this->testManager->findAllForTable(), 'json'),
                     ]);

        return $this->handleView($view);
    }

    /**
     * @param Request         $request
     * @param TestTransformer $testTransformer
     *
     * @Route("/add", name="add", methods={"GET","POST"})
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

        if ($form->isSubmitted() and $form->isValid()) {
            $this->testManager->save($test);
            $eventDispatcher->dispatch(new TestEvent($test), TestEvent::ADDED);

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
     * @Route("/view/{id}", name="view", methods={"GET"}, requirements={"id"="\d+"})
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
     *
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     *
     * @return Response
     *
     * @throws ExceptionInterface
     */
    public function edit(Request $request, Test $test, TestTransformer $testTransformer, EventDispatcherInterface $eventDispatcher): Response
    {
        $this->denyAccessUnlessGranted(TestVoter::EDIT, $test);

        $referer = $this->refererService->getFormReferer($request, 'test');

        $form = $this->createForm(EditTestType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->testManager->save($test);
            $eventDispatcher->dispatch(new TestEvent($test), TestEvent::EDITED);

            return $this->redirect($referer ?? $this->generateUrl('admin_test_list'));
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
     * @Route("/delete/{id}", name="delete", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @return Response
     */
    public function delete(Request $request, Test $test, EventDispatcherInterface $eventDispatcher): Response
    {
        $this->denyAccessUnlessGranted(TestVoter::DELETE, $test);

        $eventDispatcher->dispatch(new TestEvent($test), TestEvent::DELETED);
        $this->testManager->delete($test);

        return $request->isXmlHttpRequest() ? $this->json(['success' => true]) : $this->redirectToRoute('admin_test_list');
    }
}
