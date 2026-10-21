<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\NewPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use Jul6Art\AuthBundle\Entity\User;
use Jul6Art\AuthBundle\Manager\Interfaces\UserManagerInterface;
use Jul6Art\AuthBundle\Repository\Interfaces\UserRepositoryInterface;
use Jul6Art\CoreBundle\Controller\AbstractController;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

/**
 * Parcours « mot de passe oublié ».
 *
 * Principe : aucune réponse ne permet de savoir si une adresse est connue —
 * la page de confirmation est toujours la même. Le parcours est ouvert à tous,
 * et chaque action le déclare : pas de route sans décision d'accès.
 */
#[Route('/reset-password')]
final class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private readonly ResetPasswordHelperInterface $resetPasswordHelper,
        private readonly UserRepositoryInterface $users,
        private readonly UserManagerInterface $userManager,
    ) {
    }

    #[Route('', name: 'app_forgot_password_request', methods: ['GET', 'POST'])]
    #[IsGranted(AuthenticatedVoter::PUBLIC_ACCESS)]
    public function request(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $email */
            $email = $form->get('email')->getData();

            return $this->sendPasswordResetEmail($email, $mailer);
        }

        return $this->render('reset_password/request.html.twig', ['requestForm' => $form]);
    }

    #[Route('/check-email', name: 'app_check_email', methods: ['GET'])]
    #[IsGranted(AuthenticatedVoter::PUBLIC_ACCESS)]
    public function checkEmail(): Response
    {
        // Jeton factice quand on arrive ici sans demande : la page reste
        // identique que l'adresse existe ou non.
        $resetToken = $this->getTokenObjectFromSession() ?? $this->resetPasswordHelper->generateFakeResetToken();

        return $this->render('reset_password/check_email.html.twig', ['resetToken' => $resetToken]);
    }

    #[Route('/reset/{token}', name: 'app_reset_password', methods: ['GET', 'POST'])]
    #[IsGranted(AuthenticatedVoter::PUBLIC_ACCESS)]
    public function reset(Request $request, UserPasswordHasherInterface $passwordHasher, ?string $token = null): Response
    {
        if (null !== $token) {
            // Le jeton sort de l'URL et passe en session : il ne fuite ainsi
            // pas par le Referer des ressources chargées par la page.
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('app_reset_password');
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('Aucun jeton de réinitialisation en session.');
        }

        try {
            /** @var User $user */
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $exception) {
            $this->addErrorFlash('flash.reset.invalid', ['%reason%' => $exception->getReason()]);

            return $this->redirectToRoute('app_forgot_password_request');
        }

        $form = $this->createForm(NewPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Le jeton ne doit servir qu'une fois.
            $this->resetPasswordHelper->removeResetRequest($token);

            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
            $this->userManager->save($user);

            $this->cleanSessionAfterReset();
            $this->addSuccessFlash('flash.password.reset');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/reset.html.twig', ['resetForm' => $form]);
    }

    private function sendPasswordResetEmail(string $emailAddress, MailerInterface $mailer): RedirectResponse
    {
        $user = $this->users->findOneBy(['email' => $emailAddress]);

        if (!$user instanceof User) {
            return $this->redirectToRoute('app_check_email');
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface) {
            // Demande trop rapprochée d'une précédente : même réponse.
            return $this->redirectToRoute('app_check_email');
        }

        $mailer->send(
            (new TemplatedEmail())
                ->to((string) $user->getEmail())
                ->subject($this->trans('reset.email.subject'))
                ->htmlTemplate('reset_password/email.html.twig')
                ->context(['resetToken' => $resetToken])
        );

        $this->setTokenObjectInSession($resetToken);

        return $this->redirectToRoute('app_check_email');
    }
}
