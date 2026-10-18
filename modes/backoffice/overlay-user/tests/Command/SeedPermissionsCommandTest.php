<?php

declare(strict_types=1);

namespace App\Tests\Command;

use App\Acl\DoctrinePermissionStore;
use App\Security\DefaultRolePermissions;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * `app:permissions:seed` se relance sans défaire l'écran des droits.
 *
 * Un droit décoché reste une ligne à `granted = false` : « décidé, et refusé ». La commande passait
 * par `grantToRole()`, qui repassait cette ligne à `true` — relancer la graine après un
 * déploiement rouvrait ce qu'un administrateur avait fermé, sans rien dire.
 */
#[CoversNothing]
final class SeedPermissionsCommandTest extends KernelTestCase
{
    public function testARefusedRightStaysRefused(): void
    {
        $kernel = self::bootKernel();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);

        $seed = new CommandTester(new Application($kernel)->find('app:permissions:seed'));
        $seed->execute([]);

        $map = DefaultRolePermissions::map();
        $role = (string) array_key_first($map);
        $permission = $map[$role][0];
        $store = static::getContainer()->get(DoctrinePermissionStore::class);
        self::assertTrue($store->revokeFromRole($role, $permission, null));
        $entityManager->flush();

        $seed->execute([]);

        self::assertStringContainsString('0 permission(s) ajoutée(s)', $seed->getDisplay());
        self::assertFalse($store->isGrantedForRoles([$role], $permission, null), 'La graine a rouvert un droit refusé.');
    }
}
