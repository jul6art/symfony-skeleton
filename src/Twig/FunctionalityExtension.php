<?php

namespace App\Twig;

use App\Manager\FunctionalityManagerTrait;
use Doctrine\ORM\NonUniqueResultException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class FunctionalityExtension
 * @package App\Twig
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
        ];
    }

	/**
	 * @param string $name
	 *
	 * @return bool
	 * @throws NonUniqueResultException
	 */
    public function isFunctionalityActive(string $name): bool
    {
    	return $this->functionalityManager->isActive($name);
    }
}
