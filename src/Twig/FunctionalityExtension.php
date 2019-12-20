<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Twig;

use App\Entity\Functionality;
use App\Manager\Traits\FunctionalityManagerAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class FunctionalityExtension.
 */
class FunctionalityExtension extends AbstractExtension
{
    use FunctionalityManagerAwareTrait;

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_functionality_active', [$this->functionalityManager, 'isActive']),
            new TwigFunction('functionalities', [$this->functionalityManager, 'findAllByConfigured']),
        ];
    }
}
