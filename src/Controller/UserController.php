<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\UserEvent;
use App\Form\User\AddUserType;
use App\Form\User\EditUserType;
use App\Manager\UserManagerTrait;
use App\Security\Voter\UserVoter;
use App\Service\RefererService;
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
 * @Route("/admin/user", name="admin_")
 */
class UserController extends AbstractFOSRestController
{
    use UserManagerTrait;

    /**
     * @param UserDataTableTransformer $userDataTableTransformer
     *
     * @Route("/", name="user_list", methods={"GET"})
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
	 * @param Request $request
	 * @param UserTransformer $userTransformer
	 * @param EventDispatcherInterface $eventDispatcher
	 *
	 * @Route("/add", name="user_add", methods={"GET","POST"})
	 *
	 * @return Response
	 * @throws ExceptionInterface
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
    public function add(Request $request, UserTransformer $userTransformer, EventDispatcherInterface $eventDispatcher): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::ADD, User::class);

        $user = $this->userManager->create();

        $form = $this->createForm(AddUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	$password = $this->userManager->generatePassword();
        	$user->setPlainPassword($password);
        	$event = (new UserEvent($user))->addData('password', $password);

            $this->userManager->save($user);
            $eventDispatcher->dispatch(UserEvent::ADDED, $event);

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
     * @Route("/view/{id}", name="user_view", methods={"GET"})
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
     * @param RefererService           $refererService
     *
     * @Route("/edit/{id}", name="user_edit", methods={"GET","POST"})
     *
     * @return Response
     *
     * @throws ExceptionInterface
     */
    public function edit(Request $request, User $user, UserTransformer $userTransformer, EventDispatcherInterface $eventDispatcher, RefererService $refererService): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::EDIT, $user);

        $referer = $refererService->getFormReferer($request, 'user');

        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userManager->save($user);
            $eventDispatcher->dispatch(UserEvent::EDITED, new UserEvent($user));

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
	 * @param Request $request
	 * @param User $user
	 * @param EventDispatcherInterface $eventDispatcher
	 *
	 * @Route("/delete/{id}", name="user_delete", methods={"GET"})
	 *
	 * @return Response
	 */
    public function delete(Request $request, User $user, EventDispatcherInterface $eventDispatcher): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::DELETE, $user);

        $eventDispatcher->dispatch(UserEvent::DELETED, new UserEvent($user));
        $this->userManager->delete($user);

        return $request->isXmlHttpRequest() ? $this->json(['success' => true]) : $this->redirectToRoute('admin_user_list');
    }
}
