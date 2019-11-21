<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class AuditExtension.
 */
class AuditExtension extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('blame_audit', [$this, 'blameAudit']),
        ];
    }

    /**
     * @param string|null $idAsString
     * @param array       $usernameList
     *
     * @return string|null
     */
    public function blameAudit(string $idAsString = null, array $usernameList): ?string
    {
        if (null !== $idAsString) {
            $id = (int) $idAsString;

            if (array_key_exists($id, $usernameList)) {
                return $usernameList[$id];
            }
        }

        return null;
    }
}
