<?php

declare(strict_types=1);

namespace App\Tests\DataTable;

use App\Entity\User;
use App\Security\PermissionCodes;
use App\Tests\Support\QueryParameters;
use Jul6Art\AclBundle\Security\PermissionDecisionService;
use Jul6Art\ApiBundle\Filter\CaseInsensitiveOrderFilter;
use Jul6Art\ApiBundle\Filter\ConcatOrderFilter;
use Jul6Art\DatatableBundle\DataTable\AbstractDataTableConfigProvider;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

use function is_string;
use function sprintf;

/**
 * Toute colonne déclarée triable est réellement ordonnable côté API.
 *
 * ⚠️ **`column()` rend une colonne triable PAR DÉFAUT** : l'aide du bundle écrit
 * `'sortField' => $sortField ?? $data`, donc déclarer une colonne suffit à PROMETTRE un tri. Si
 * l'entité ne réclame pas la propriété, API Platform jette le paramètre en SILENCE — l'en-tête
 * s'allume, l'ordre ne bouge pas, et l'utilisateur croit trier. Aucune erreur, aucun 400, une
 * réponse 200 parfaitement valide.
 *
 * Sur le projet d'où vient ce test, **huit colonnes** étaient dans ce cas sur cinq tables — dont
 * quatre invisibles, parce qu'offertes sans être montrées (`hidden: true`), donc vues seulement
 * par qui les coche. La règle était pourtant écrite dans la checklist : elle demandait de croiser
 * à la main un provider et une entité, et c'est exactement le genre de vérification qu'une revue
 * abrège. Elle est désormais exécutée, comme celle des filtres de plage
 * (`DateRangeFilterMappingTest`).
 *
 * ⚠️ Le test lit **DEUX** filtres. `fullName` n'est pas une colonne Doctrine : seul le
 * `ConcatOrderFilter` d'`api-bundle` sait l'ordonner (`['fullName' => ['firstName', 'lastName']]`).
 * Un garde-fou qui ne connaîtrait que le gabarit `order[:property]` refuserait une configuration correcte, et
 * pousserait à retirer un tri qui marche.
 *
 * ⚠️ Un provider nouveau s'ajoute ICI, dans `providers()` : la liste est explicite parce qu'un
 * oubli doit se voir en revue, pas se deviner.
 */
#[CoversNothing]
final class SortFieldMappingTest extends KernelTestCase
{
    /**
     * Provider → entité qu'il liste.
     *
     * @return iterable<string, array{class-string<AbstractDataTableConfigProvider>, class-string}>
     */
    public static function providers(): iterable
    {
        yield 'users' => [\App\DataTable\UserDataTableConfigProvider::class, User::class];
    }

    /**
     * @param class-string<AbstractDataTableConfigProvider> $providerClass
     * @param class-string                                  $entityClass
     */
    #[DataProvider('providers')]
    public function testEverySortableColumnIsOrderableOnTheEntity(string $providerClass, string $entityClass): void
    {
        $orderable = array_merge(
            self::orderFilterProperties($entityClass),
            self::concatOrderFilterProperties($entityClass),
        );

        foreach (self::sortableColumns($this->buildProvider($providerClass)) as $sortField) {
            self::assertContains(
                $sortField,
                $orderable,
                sprintf(
                    '%s déclare la colonne « %s » triable, que %s ne mappe pas : le tri sera IGNORÉ en '
                    .'silence — l\'en-tête s\'allume et l\'ordre ne bouge pas. Ajouter la propriété à '
                    .'`properties` du #[QueryParameter(key: \'order[:property]\', …)], ou passer la colonne en '
                    .'readOnlyColumn() si '
                    .'elle n\'est pas ordonnable (une valeur calculée en PHP ne l\'est jamais).',
                    $providerClass,
                    $sortField,
                    $entityClass,
                ),
            );
        }
    }

    /**
     * Le test ne prouve rien s'il ne regarde aucune colonne : on l'affirme plutôt que l'espérer.
     *
     * @param class-string<AbstractDataTableConfigProvider> $providerClass
     * @param class-string                                  $entityClass
     */
    #[DataProvider('providers')]
    public function testTheTableActuallyHasASortableColumnToCheck(string $providerClass, string $entityClass): void
    {
        self::assertNotSame([], self::sortableColumns($this->buildProvider($providerClass)), $providerClass);
        self::assertNotSame('', $entityClass);
    }

    /**
     * @param class-string<AbstractDataTableConfigProvider> $providerClass
     */
    private function buildProvider(string $providerClass): AbstractDataTableConfigProvider
    {
        self::bootKernel();
        $container = static::getContainer();

        $provider = new $providerClass(
            $container->get(TranslatorInterface::class),
            $container->get(PermissionDecisionService::class),
        );
        self::assertInstanceOf(AbstractDataTableConfigProvider::class, $provider);
        self::assertNotSame('', PermissionCodes::USER_READ);

        return $provider;
    }

    /**
     * @return list<string> les `sortField` des colonnes triables
     */
    private static function sortableColumns(AbstractDataTableConfigProvider $provider): array
    {
        $fields = [];

        foreach ($provider->getColumns() as $column) {
            // ⚠️ `readOnlyColumn()` ne pose PAS de `sortField` et ajoute `orderable => false` : les
            // deux marques disent la même chose, et lire les deux évite de dépendre d'un détail
            // d'implémentation de l'aide.
            $sortField = $column['sortField'] ?? null;

            if (is_string($sortField) && false !== ($column['orderable'] ?? true)) {
                $fields[] = $sortField;
            }
        }

        return $fields;
    }

    /**
     * @param class-string $entityClass
     *
     * @return list<string> les propriétés du gabarit `order[:property]` de l'entité
     */
    private static function orderFilterProperties(string $entityClass): array
    {
        return self::filterProperties($entityClass, CaseInsensitiveOrderFilter::class);
    }

    /**
     * Les propriétés VIRTUELLES du `ConcatOrderFilter` : sa configuration est un tableau
     * `['fullName' => ['firstName', 'lastName']]`, donc ce sont les CLÉS qui nomment le tri.
     *
     * @param class-string $entityClass
     *
     * @return list<string>
     */
    private static function concatOrderFilterProperties(string $entityClass): array
    {
        return self::filterProperties($entityClass, ConcatOrderFilter::class);
    }

    /**
     * @param class-string $entityClass
     * @param class-string $filterClass
     *
     * @return list<string>
     */
    private static function filterProperties(string $entityClass, string $filterClass): array
    {
        return QueryParameters::propertiesFilteredBy($entityClass, $filterClass);
    }
}
