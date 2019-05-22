<?php

namespace App\Twig;

use App\Entity\User;
use App\Manager\FunctionalityManager;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class FunctionalityExtension
 * @package App\Twig
 */
class FunctionalityExtension extends AbstractExtension
{
	/**
	 * @var FunctionalityManager
	 */
	private $functionalityManager;

	/**
	 * FunctionalityExtension constructor.
	 *
	 * @param FunctionalityManager $functionalityManager
	 */
	public function __construct(FunctionalityManager $functionalityManager)
	{
		$this->functionalityManager = $functionalityManager;
	}

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
