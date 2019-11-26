<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Manager;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Lexik\Bundle\TranslationBundle\Entity\Translation;
use Lexik\Bundle\TranslationBundle\Entity\TransUnit;

/**
 * Class TranslationManager.
 */
class TranslationManager extends AbstractManager
{
    /**
     * @param QueryBuilder $builder
     *
     * @return $this
     */
    private function joinTransUnit(QueryBuilder $builder): self
    {
        if (!\in_array('u', $builder->getAllAliases())) {
            $builder
                ->leftJoin(TransUnit::class, 'u', Join::WITH, 't.transUnit = u.id');
        }

        return $this;
    }

    /**
     * @param QueryBuilder $builder
     * @param string       $domain
     *
     * @return $this
     */
    private function filterByDomain(QueryBuilder $builder, string $domain): self
    {
        $this->joinTransUnit($builder);

        $builder
            ->andWhere($builder->expr()->eq('u.domain', ':domain'))
            ->setParameter('domain', $domain, Type::STRING);

        return $this;
    }

    /**
     * @param QueryBuilder $builder
     * @param string       $key
     *
     * @return $this
     */
    private function filterByKey(QueryBuilder $builder, string $key): self
    {
        $this->joinTransUnit($builder);

        $builder
            ->andWhere($builder->expr()->eq('u.key', ':key'))
            ->setParameter('key', $key, Type::STRING);

        return $this;
    }

    /**
     * @param QueryBuilder $builder
     * @param string       $locale
     *
     * @return $this
     */
    private function filterByLocale(QueryBuilder $builder, string $locale): self
    {
        $builder
            ->andWhere($builder->expr()->eq('t.locale', ':locale'))
            ->setParameter('locale', $locale, Type::STRING);

        return $this;
    }

    /**
     * @param string $domain
     * @param string $key
     * @param string $locale
     *
     * @return Translation[]
     */
    public function findByDomainAndKeyAndLocale(string $domain, string $key, string $locale): array
    {
        $builder = $this->entityManager
            ->getRepository(Translation::class)
            ->createQueryBuilder('t');

        $this
            ->filterByDomain($builder, $domain)
            ->filterByKey($builder, $key)
            ->filterByLocale($builder, $locale);

        return $builder->getQuery()->getResult();
    }

    /**
     * @param Translation $translation
     * @param string      $value
     *
     * @return $this
     */
    public function update(Translation $translation, string $value): self
    {
        $translation->setModifiedManually(true);
        $translation->setContent($value);

        return $this;
    }

    /**
     * @param array  $translations
     * @param string $value
     *
     * @return $this
     */
    public function updateMultiple(array $translations, string $value): self
    {
        foreach ($translations as $translation) {
            $this->update($translation, $value);
        }

        return $this;
    }
}
