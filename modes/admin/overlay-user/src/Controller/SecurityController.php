<?php

declare(strict_types=1);

namespace App\Controller;

use Jul6Art\CoreBundle\Controller\AbstractController;
use LogicException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    #[IsGranted(AuthenticatedVoter::PUBLIC_ACCESS)]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if (null !== $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * Seule famille de routes sans décision d'accès dans le code, et pour cause :
     * la clé `logout` du pare-feu intercepte la requête, cette action n'est
     * jamais exécutée. La règle d'accès, c'est le pare-feu.
     */
    #[Route('/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(): never
    {
        throw new LogicException('Cette action est interceptée par la clé "logout" du pare-feu.');
    }
}
