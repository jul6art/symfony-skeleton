<?php

declare(strict_types=1);

namespace App\Tests\Support;

use ApiPlatform\Doctrine\Orm\Filter\ChainFilter;
use ApiPlatform\Metadata\QueryParameter;
use ReflectionClass;
use ReflectionProperty;

use function is_array;
use function is_object;
use function is_string;
use function preg_match;

/**
 * Les `#[QueryParameter]` qu'une entité déclare, lus comme les gardes de `tests/DataTable` et
 * `tests/Api` en ont besoin.
 *
 * ⚠️ **Une seule lecture, parce que six gardes la faisaient chacun.** Jusqu'à API Platform 4.4, chaque
 * garde relisait les arguments bruts de `#[ApiFilter]` (`$arguments['properties']`, forme liste ou
 * carte). Les filtres se déclarent désormais par `#[QueryParameter]` (l'`#[ApiFilter]` est déprécié,
 * retiré en 6.0) : c'est l'attribut INSTANCIÉ qui se lit ici, avec son filtre — une classe, pas une
 * chaîne à comparer.
 *
 * ⚠️ Lus par RÉFLEXION, jamais par une expression régulière sur le fichier : l'attribut peut s'écrire
 * sur plusieurs lignes, et un `#[QueryParameter(...)]` cité dans un commentaire se lirait comme un vrai.
 */
final class QueryParameters
{
    /**
     * Les propriétés que couvre un filtre de l'une de ces classes.
     *
     * Une propriété est nommée par `properties` (un gabarit `order[:property]`, une recherche
     * `search`), sinon par `property`, sinon par la clé elle-même : `order[fullName]` d'un
     * `ConcatOrderFilter` nomme un tri qui n'est pas une colonne, et n'a donc pas de `property`.
     *
     * @param class-string $entityClass
     * @param class-string ...$filterClasses
     *
     * @return list<string>
     */
    public static function propertiesFilteredBy(string $entityClass, string ...$filterClasses): array
    {
        $properties = [];

        foreach (self::filtersOf($entityClass, ...$filterClasses) as [$parameter]) {
            foreach ($parameter->getProperties() ?? [self::nameOf($parameter)] as $property) {
                $properties[] = $property;
            }
        }

        return $properties;
    }

    /**
     * Chaque paramètre dont le filtre est de l'une de ces classes, avec son filtre.
     *
     * @param class-string $entityClass
     * @param class-string ...$filterClasses
     *
     * @return list<array{QueryParameter, object}>
     */
    public static function filtersOf(string $entityClass, string ...$filterClasses): array
    {
        $found = [];

        foreach (new ReflectionClass($entityClass)->getAttributes(QueryParameter::class) as $attribute) {
            $parameter = $attribute->newInstance();
            $filter = $parameter->getFilter();

            if (!is_object($filter)) {
                continue;
            }

            // ⚠️ Un `ChainFilter` porte plusieurs filtres sur UNE clé (`?date=2026` et
            // `?date[after]=…`) : ce sont eux qui comptent, pas l'enveloppe.
            $filters = $filter instanceof ChainFilter ? self::configurationOf($filter, 'filters') : [$filter];

            foreach ($filters as $member) {
                foreach ($filterClasses as $filterClass) {
                    if (is_object($member) && $member instanceof $filterClass) {
                        $found[] = [$parameter, $member];

                        break;
                    }
                }
            }
        }

        return $found;
    }

    /**
     * La propriété d'un paramètre, ou le nom que porte sa clé (`order[fullName]` → `fullName`).
     */
    public static function nameOf(QueryParameter $parameter): string
    {
        $property = $parameter->getProperty();

        if (is_string($property)) {
            return $property;
        }

        $key = (string) $parameter->getKey();

        return 1 === preg_match('/^\w+\[(\w+)\]$/', $key, $matches) ? $matches[1] : $key;
    }

    /**
     * Un argument de constructeur qu'un filtre garde pour lui — les rangs d'un `RankedOrderFilter`.
     *
     * @return array<mixed>
     */
    public static function configurationOf(object $filter, string $property): array
    {
        $value = new ReflectionProperty($filter, $property)->getValue($filter);

        return is_array($value) ? $value : [];
    }
}
