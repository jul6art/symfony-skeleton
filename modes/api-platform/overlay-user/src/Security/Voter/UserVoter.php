<?php

declare(strict_types=1);

namespace App\Security\Voter;

use Jul6Art\AuthBundle\Entity\User;
use Jul6Art\CoreBundle\Security\Voter\AbstractVoter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Décisions d'accès portant sur un compte.
 *
 * Un attribut par action exposée : c'est ce vocabulaire que citent les
 * opérations dans leur clé `security:`, jamais un rôle. Les cinq attributs
 * sont déclarés même si l'API n'expose pas encore toutes les actions — une
 * nouvelle opération nomme un attribut existant plutôt que d'en inventer un.
 *
 * Un compte en sujet — ou aucun, pour la liste et la création.
 *
 * @extends AbstractVoter<string, User|null>
 */
final class UserVoter extends AbstractVoter
{
    public const LIST = 'USER_LIST';

    public const VIEW = 'USER_VIEW';

    public const CREATE = 'USER_CREATE';

    public const EDIT = 'USER_EDIT';

    public const DELETE = 'USER_DELETE';

    protected function attributes(): array
    {
        return [self::LIST, self::VIEW, self::CREATE, self::EDIT, self::DELETE];
    }

    protected function subjects(): array
    {
        return [User::class];
    }

    protected function decide(string $attribute, mixed $subject, UserInterface $user): bool
    {
        $isAdmin = $this->hasRole(User::ROLE_ADMIN);

        return match ($attribute) {
            // Parcourir l'annuaire et créer un compte : l'administration.
            self::LIST, self::CREATE => $isAdmin,
            // Son propre compte, ou n'importe lequel pour un administrateur.
            self::VIEW, self::EDIT => $isAdmin || $this->isSelf($subject, $user),
            // On ne supprime pas le compte porté par le jeton présenté : la
            // règle vit ici, pas dans l'expression de l'opération.
            self::DELETE => $isAdmin && $subject instanceof User && !$this->isSelf($subject, $user),
            default => false,
        };
    }

    private function isSelf(mixed $subject, UserInterface $user): bool
    {
        return $subject instanceof User && $subject->getUserIdentifier() === $user->getUserIdentifier();
    }
}
