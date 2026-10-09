<?php

declare(strict_types=1);

namespace App\Tests\DataTable;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use App\Entity\User;
use App\Security\PermissionCodes;
use App\Tests\Support\QueryParameters;
use Jul6Art\AclBundle\Security\PermissionDecisionService;
use Jul6Art\DatatableBundle\DataTable\AbstractDataTableConfigProvider;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use function in_array;
use function is_string;
use function sprintf;

/**
 * Les deux règles de plage de dates, rendues exécutables pour CHAQUE tableau du projet.
 *
 * 1. **Toute colonne date porte son filtre de plage.** C'est la demande du 2026-08-24 : « avec
 *    beaucoup de données on serait noyé sous les archives sans ce filtre ». Une liste qui grossit
 *    sans moyen de remonter à une période devient illisible, et le manque ne se voit qu'à l'usage,
 *    quand il est trop tard pour l'ajouter sans migrer des préférences enregistrées.
 *
 * 2. **Tout filtre de plage est mappé côté API** (item A4 de la checklist datatable). Un
 *    `dateRangeFilter()` sans `DateFilter` sur l'entité s'affiche et ne filtre RIEN : API Platform
 *    jette en silence un paramètre qu'aucun filtre ne réclame. C'était le cas de `createdAt` sur la
 *    table des comptes depuis le premier jour — 103 lignes avant filtre, 103 après.
 *
 * Aucun test de configuration n'attrapait le second : les deux configurations étaient correctes
 * séparément, c'est leur CORRESPONDANCE qui manquait. C'est exactement ce que ce test vérifie.
 *
 * ⚠️ Un provider ajouté au projet s'ajoute ICI, dans `providers()`. La liste est explicite plutôt
 * que découverte par le conteneur : un provider a besoin de ses dépendances pour s'instancier, et
 * les deviner rendrait l'échec du test illisible le jour où l'une change.
 */
#[CoversNothing]
final class DateRangeFilterMappingTest extends KernelTestCase
{
    /**
     * Les rendus de date que le socle sait RÉSOUDRE. Le vocabulaire vient du contrôleur Stimulus du
     * datatable-bundle (`getRenderer()`), qui n'en expose que deux.
     *
     * ⚠️ Cette liste contenait `datetime` et `dateTime`, qui n'existent NULLE PART — un rendu
     * inconnu retombe sur `null` et la cellule affiche la valeur ISO brute. Et elle oubliait
     * `dateOnly`, donc une colonne de date pure échappait à la règle n° 1. Les deux erreurs sont
     * sans effet ici, où la seule colonne date est rendue par `date` ; elles se paieraient à la
     * première colonne `date_immutable` ajoutée, et un garde-fou qui ne garde pas est pire que pas
     * de garde-fou (superp, rapport 2026-08-24 P2).
     *
     * @var list<string>
     */
    private const array DATE_RENDERERS = ['date', 'dateOnly'];

    /**
     * Tout ce qui, dans une clef `render`, DÉSIGNE une date — y compris les rendus inexistants, que
     * le test doit voir pour les refuser.
     *
     * @var list<string>
     */
    private const array DATE_LIKE_RENDERERS = ['date', 'dateOnly', 'datetime', 'dateTime'];

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
    public function testEveryDateColumnOffersItsRangeFilter(string $providerClass, string $entityClass): void
    {
        $provider = $this->buildProvider($providerClass);
        $filtered = self::dateRangeColumns($provider);

        foreach (self::dateColumns($provider) as $column) {
            self::assertContains(
                $column,
                $filtered,
                sprintf(
                    '%s affiche la colonne date « %s » sans filtre de plage. Avec du volume, la liste devient '
                    .'illisible : ajouter `$this->dateRangeFilter(\'%s\', \'%s\', …)` dans getFilters().',
                    $providerClass,
                    $column,
                    $column,
                    $column,
                ),
            );
        }
    }

    /**
     * @param class-string<AbstractDataTableConfigProvider> $providerClass
     * @param class-string                                  $entityClass
     */
    #[DataProvider('providers')]
    public function testEveryRangeFilterIsMappedOnTheEntity(string $providerClass, string $entityClass): void
    {
        $mapped = self::dateFilteredProperties($entityClass);

        foreach (self::dateRangeColumns($this->buildProvider($providerClass)) as $param) {
            self::assertContains(
                $param,
                $mapped,
                sprintf(
                    '%s déclare un filtre de plage sur « %s » que %s ne mappe pas : le filtre s\'affichera et ne '
                    .'retirera aucune ligne. Ajouter #[QueryParameter(key: \'%s\', filter: new DateFilter(), property: \'%s\')].',
                    $providerClass,
                    $param,
                    $entityClass,
                    $param,
                    $param,
                ),
            );
        }
    }

    /**
     * Une colonne ne déclare pas un rendu que le socle ne sait pas résoudre.
     *
     * `render: 'datetime'` n'existe pas : `getRenderer()` rend `null`, DataTables reçoit
     * `render: null` et la cellule affiche l'ISO brut (`2026-08-24T14:26:58+00:00`). Rien ne le
     * signale — ni PHPStan, ni la console du navigateur.
     *
     * @param class-string<AbstractDataTableConfigProvider> $providerClass
     * @param class-string                                  $entityClass
     */
    #[DataProvider('providers')]
    public function testNoColumnDeclaresARendererThatDoesNotExist(string $providerClass, string $entityClass): void
    {
        $provider = $this->buildProvider($providerClass);

        foreach ($provider->getColumns() as $column) {
            $render = $column['render'] ?? null;
            if (!is_string($render) || !in_array($render, self::DATE_LIKE_RENDERERS, true)) {
                continue;
            }

            self::assertContains(
                $render,
                self::DATE_RENDERERS,
                sprintf(
                    '%s déclare `render: \'%s\'` sur « %s » (%s). Le socle ne résout que `date` et `dateOnly` : '
                    .'un rendu inconnu retombe sur null et la cellule affiche la valeur ISO brute.',
                    $providerClass,
                    $render,
                    (string) ($column['data'] ?? '?'),
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
    public function testTheTableActuallyHasADateColumnToCheck(string $providerClass, string $entityClass): void
    {
        self::assertNotSame([], self::dateColumns($this->buildProvider($providerClass)), $providerClass);
    }

    /**
     * @param class-string<AbstractDataTableConfigProvider> $providerClass
     */
    private function buildProvider(string $providerClass): AbstractDataTableConfigProvider
    {
        self::bootKernel();
        $container = static::getContainer();

        $provider = new $providerClass(
            $container->get(\Symfony\Contracts\Translation\TranslatorInterface::class),
            $container->get(PermissionDecisionService::class),
        );
        self::assertInstanceOf(AbstractDataTableConfigProvider::class, $provider);

        // Une constante référencée pour que l'analyse statique voie l'usage de PermissionCodes,
        // que le provider consomme et dont ce test dépend indirectement.
        self::assertNotSame('', PermissionCodes::USER_READ);

        return $provider;
    }

    /** @return list<string> les `data` des colonnes rendues en date */
    private static function dateColumns(AbstractDataTableConfigProvider $provider): array
    {
        $columns = [];

        foreach ($provider->getColumns() as $column) {
            $render = $column['render'] ?? null;
            $data = $column['data'] ?? null;

            if (is_string($render) && is_string($data) && in_array($render, self::DATE_LIKE_RENDERERS, true)) {
                $columns[] = $data;
            }
        }

        return $columns;
    }

    /** @return list<string> les `param` des filtres de type `daterange` */
    private static function dateRangeColumns(AbstractDataTableConfigProvider $provider): array
    {
        $params = [];

        foreach ($provider->getFilters() as $filter) {
            $param = $filter['param'] ?? null;

            if ('daterange' === ($filter['type'] ?? null) && is_string($param)) {
                $params[] = $param;
            }
        }

        return $params;
    }

    /**
     * @param class-string $entityClass
     *
     * @return list<string> les propriétés couvertes par un `DateFilter`
     */
    private static function dateFilteredProperties(string $entityClass): array
    {
        return QueryParameters::propertiesFilteredBy($entityClass, DateFilter::class);
    }
}
