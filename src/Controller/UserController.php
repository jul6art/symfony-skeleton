<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Controller;

use App\Entity\User;
use App\Event\UserEvent;
use App\Form\User\AddUserType;
use App\Form\User\EditUserType;
use App\Manager\UserManagerTrait;
use App\Security\Voter\UserVoter;
use App\Service\RefererServiceTrait;
use App\Transformer\UserDataTableTransformer;
use App\Transformer\UserTransformer;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("%admin_route_prefix%/user", name="admin_user_")
 */
class UserController extends AbstractFOSRestController
{
    use RefererServiceTrait;
    use UserManagerTrait;

    /**
     * @param UserDataTableTransformer $userDataTableTransformer
     *
     * @Route("/", name="list", methods={"GET"})
     *
     * @return Response
     *
     * @throws ExceptionInterface
     */
    public function index(UserDataTableTransformer $userDataTableTransformer): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::LIST, User::class);

        $serializer = new Serializer([$userDataTableTransformer]);

        $view = $this->view()
                     ->setTemplate('user/list.html.twig')
                     ->setTemplateData([
                         'users' => $serializer->normalize($this->userManager->findAllForTable(), 'json', [
                         ]),
                     ]);

        return $this->handleView($view);
    }

    /**
     * @param Request                  $request
     * @param UserTransformer          $userTransformer
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @Route("/add", name="add", methods={"GET","POST"})
     *
     * @return Response
     *
     * @throws ExceptionInterface
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function add(Request $request, UserTransformer $userTransformer, EventDispatcherInterface $eventDispatcher): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::ADD, User::class);

        $user = $this->userManager->create();

        $form = $this->createForm(AddUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $password = $this->userManager->generatePassword();
            $user->setPlainPassword($password);
            $event = (new UserEvent($user))
                ->addData('password', $password)
                ->addData('createdBy', $this->getUser());

            $this->userManager
                ->presetSettings($user)
                ->activate($user)
                ->save($user);

            $eventDispatcher->dispatch($event, UserEvent::ADDED);

            return $this->redirectToRoute('admin_user_list');
        }

        $serializer = new Serializer([$userTransformer]);

        $view = $this->view()
                     ->setTemplate('user/add.html.twig')
                     ->setTemplateData([
                         'user' => $serializer->normalize($user, 'json'),
                         'form' => $form->createView(),
                     ]);

        return $this->handleView($view);
    }

    /**
     * @param User            $user
     * @param UserTransformer $userTransformer
     *
     * @Route("/view/{id}", name="view", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @return Response
     *
     * @throws ExceptionInterface
     */
    public function show(User $user, UserTransformer $userTransformer): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::VIEW, $user);

        $serializer = new Serializer([$userTransformer]);

        $view = $this->view()
                     ->setTemplate('user/view.html.twig')
                     ->setTemplateData([
                         'entity' => $user,
                         'user' => $serializer->normalize($user, 'json'),
                     ]);

        return $this->handleView($view);
    }

    /**
     * @param Request                  $request
     * @param User                     $user
     * @param UserTransformer          $userTransformer
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     *
     * @return Response
     *
     * @throws ExceptionInterface
     */
    public function edit(Request $request, User $user, UserTransformer $userTransformer, EventDispatcherInterface $eventDispatcher): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::EDIT, $user);

        $referer = $this->refererService->getFormReferer($request, 'user');

        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->userManager->save($user);
            $eventDispatcher->dispatch(new UserEvent($user), UserEvent::EDITED);

            return $this->redirect($referer ?? $this->generateUrl('admin_user_list'));
        }

        $serializer = new Serializer([$userTransformer]);

        $view = $this->view()
                     ->setTemplate('user/edit.html.twig')
                     ->setTemplateData([
                         'entity' => $user,
                         'user' => $serializer->normalize($user, 'json'),
                         'form' => $form->createView(),
                         'referer' => $referer,
                     ]);

        return $this->handleView($view);
    }

    /**
     * @param Request                  $request
     * @param User                     $user
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @Route("/delete/{id}", name="delete", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @return Response
     */
    public function delete(Request $request, User $user, EventDispatcherInterface $eventDispatcher): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::DELETE, $user);

        $eventDispatcher->dispatch(new UserEvent($user), UserEvent::DELETED);
        $this->userManager->delete($user);

        return $request->isXmlHttpRequest() ? $this->json(['success' => true]) : $this->redirectToRoute('admin_user_list');
    }

    /**
     * @param Request                  $request
     * @param User                     $user
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @Route("/delete/self", name="delete_self", methods={"GET"})
     *
     * @return Response
     */
    public function selfDelete(Request $request, EventDispatcherInterface $eventDispatcher): Response
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted(UserVoter::SELF_DELETE, $user);

        $eventDispatcher->dispatch(new UserEvent($user), UserEvent::DELETED);
        $this->userManager
            ->delete($user)
            ->logout();

        return $request->isXmlHttpRequest() ? $this->json(['success' => true]) : $this->redirectToRoute('fos_user_security_logout');
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @Route("/impersonate/{id}", name="impersonate", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @return Response
     */
    public function impersonate(Request $request, User $user): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::IMPERSONATE, $user);

        return $this->redirect(sprintf(
            '%s?_switch_user=%s',
            $this->generateUrl('admin_homepage'),
            $user->getUsername()
        ));
    }
}
