<?php

declare(strict_types=1);

namespace App\Acl;

use App\Entity\RolePermission;
use App\Entity\User;
use App\Entity\UserPermissionOverride;
use App\Repository\RolePermissionRepository;
use App\Repository\UserPermissionOverrideRepository;
use Doctrine\ORM\EntityManagerInterface;
use Jul6Art\AclBundle\Contract\AclTenantInterface;
use Jul6Art\AclBundle\Contract\AclUserInterface;
use Jul6Art\AclBundle\Contract\PermissionStoreInterface;
use Override;

use function in_array;

/**
 * Le côté écriture : ce que l'écran de gestion des rôles et la délégation de permissions utilisent.
 *
 * Séparé de la lecture à dessein — une application qui lit des permissions sans jamais en déléguer
 * n'implémente pas six méthodes de mutation pour faire marcher un voter.
 *
 * Chaque méthode dit si elle a CHANGÉ quelque chose, ce qui permet à l'appelant de distinguer
 * « accordé » de « était déjà accordé » sans relire.
 *
 * ⚠️ Aucune méthode ne flush : l'appelant décide de la transaction. Un `flush()` par grant
 * transformerait l'enregistrement d'un écran de cinquante cases en cinquante transactions.
 */
final readonly class DoctrinePermissionStore implements PermissionStoreInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RolePermissionRepository $rolePermissions,
        private UserPermissionOverrideRepository $overrides,
    ) {
    }

    #[Override]
    public function grantToUser(AclUserInterface $target, string $permission): bool
    {
        return $this->setUserOverride($target, $permission, true);
    }

    #[Override]
    public function denyToUser(AclUserInterface $target, string $permission): bool
    {
        return $this->setUserOverride($target, $permission, false);
    }

    #[Override]
    public function removeUserOverride(AclUserInterface $target, string $permission): bool
    {
        if (!$target instanceof User) {
            return false;
        }

        $existing = $this->overrides->findOneBy(['user' => $target, 'permission' => $permission]);
        if (!$existing instanceof UserPermissionOverride) {
            return false;
        }

        $this->entityManager->remove($existing);

        return true;
    }

    /**
     * @param list<string> $roles
     */
    #[Override]
    public function isGrantedForRoles(array $roles, string $permission, ?AclTenantInterface $tenant): bool
    {
        return in_array($permission, $this->rolePermissions->findGrantedForRoles($roles), true);
    }

    #[Override]
    public function grantToRole(string $roleCode, string $permission, ?AclTenantInterface $tenant): bool
    {
        $existing = $this->rolePermissions->findOneForRole($roleCode, $permission);

        if ($existing instanceof RolePermission) {
            if ($existing->isGranted()) {
                return false;
            }

            $existing->setGranted(true);

            return true;
        }

        $this->entityManager->persist(
            new RolePermission()->setRoleCode($roleCode)->setPermission($permission)->setGranted(true),
        );

        return true;
    }

    /**
     * Pose la valeur par défaut d'un rôle si personne n'en a jamais décidé — et seulement alors.
     * C'est ce qui rend `app:permissions:seed` relançable sans défaire l'écran des droits :
     * `grantToRole()` repasserait à `true` une ligne qu'un administrateur a mise à `false`.
     */
    public function seedRole(string $roleCode, string $permission): bool
    {
        if ($this->rolePermissions->findOneForRole($roleCode, $permission) instanceof RolePermission) {
            return false;
        }

        $this->entityManager->persist(
            new RolePermission()->setRoleCode($roleCode)->setPermission($permission)->setGranted(true),
        );

        return true;
    }

    #[Override]
    public function revokeFromRole(string $roleCode, string $permission, ?AclTenantInterface $tenant): bool
    {
        $existing = $this->rolePermissions->findOneForRole($roleCode, $permission);
        if (!$existing instanceof RolePermission || !$existing->isGranted()) {
            return false;
        }

        // On garde la ligne à `false` plutôt que de la supprimer : l'écran distingue ainsi
        // « jamais décidé » de « décidé, et refusé », ce qui est exactement ce que quelqu'un qui
        // vient de décocher a besoin de revoir.
        $existing->setGranted(false);

        return true;
    }

    private function setUserOverride(AclUserInterface $target, string $permission, bool $granted): bool
    {
        if (!$target instanceof User) {
            return false;
        }

        $existing = $this->overrides->findOneBy(['user' => $target, 'permission' => $permission]);

        if ($existing instanceof UserPermissionOverride) {
            if ($existing->isGranted() === $granted) {
                return false;
            }

            $existing->setGranted($granted);

            return true;
        }

        $this->entityManager->persist(
            new UserPermissionOverride()->setUser($target)->setPermission($permission)->setGranted($granted),
        );

        return true;
    }
}
