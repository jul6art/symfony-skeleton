<?php

namespace App\Twig;

use App\Entity\Functionality;
use App\Manager\FunctionalityManagerTrait;
use Doctrine\ORM\NonUniqueResultException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class FunctionalityExtension.
 */
class FunctionalityExtension extends AbstractExtension
{
    use FunctionalityManagerTrait;

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_functionality_active', [$this, 'isFunctionalityActive']),
            new TwigFunction('functionalities', [$this, 'getFunctionalities']),
        ];
    }

    /**
     * @param string $name
     *
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function isFunctionalityActive(string $name): bool
    {
        return $this->functionalityManager->isActive($name);
    }

    /**
     * @return Functionality[]
     */
    public function getFunctionalities(): array
    {
        return $this->functionalityManager->findAllByConfigured();
    }
}
