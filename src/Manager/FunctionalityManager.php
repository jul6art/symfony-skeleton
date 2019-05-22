<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/04/2019
 * Time: 13:09
 */

namespace App\Manager;

use App\Entity\Functionality;
use App\Repository\FunctionalityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class FunctionalityManager
 * @package App\Manager
 */
class FunctionalityManager extends AbstractManager
{
	/**
	 * @var FunctionalityRepository
	 */
	private $functionalityRepository;

	/**
	 * FunctionalityManager constructor.
	 *
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(EntityManagerInterface $entityManager) {
		parent::__construct($entityManager);
		$this->functionalityRepository = $this->entityManager->getRepository(Functionality::class);
	}

	/**
	 * @return Functionality
	 */
	public function create(): Functionality
	{
		return (new Functionality())
			->setActive(true);
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 * @throws NonUniqueResultException
	 */
	public function isActive(string $name): bool
	{
		$functionality = $this->findOneByName($name);

		if (!is_null($functionality)) {
			return $functionality->isActive();
		}

		return false;
	}

	/**
	 * @param string $name
	 *
	 * @return Functionality|null
	 * @throws NonUniqueResultException
	 */
	public function findOneByName(string $name): ?Functionality
	{
		return $this->functionalityRepository->findOneByName($name);
	}
}