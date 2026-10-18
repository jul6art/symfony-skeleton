<?php

declare(strict_types=1);

namespace App\Command;

use App\Acl\DoctrinePermissionStore;
use App\Security\DefaultRolePermissions;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function sprintf;

/**
 * Pose les permissions par défaut de chaque rôle, telles que `DefaultRolePermissions` les décrit.
 *
 * Idempotente : une permission déjà DÉCIDÉE — accordée, ou refusée depuis l'écran des droits —
 * n'est pas retouchée, donc la commande peut être rejouée après un déploiement qui ajoute des
 * codes. Ce que quelqu'un a décoché est une décision, pas une dérive à corriger : la commande
 * passait par `grantToRole()`, qui rouvrait un droit refusé ; elle passe par `seedRole()`.
 */
#[AsCommand(name: 'app:permissions:seed', description: 'Pose les permissions par défaut des rôles')]
final class SeedPermissionsCommand extends Command
{
    public function __construct(
        private readonly DoctrinePermissionStore $store,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $granted = 0;

        foreach (DefaultRolePermissions::map() as $role => $permissions) {
            foreach ($permissions as $permission) {
                if ($this->store->seedRole($role, $permission)) {
                    ++$granted;
                }
            }
        }

        $this->entityManager->flush();

        $io->success(sprintf('%d permission(s) ajoutée(s).', $granted));

        return Command::SUCCESS;
    }
}
