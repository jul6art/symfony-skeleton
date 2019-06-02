<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/04/2019
 * Time: 13:09.
 */

namespace App\Manager;

use App\Entity\Functionality;
use App\Factory\FunctionalityFactory;
use App\Repository\FunctionalityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class FunctionalityManager.
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
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->functionalityRepository = $this->entityManager->getRepository(Functionality::class);
    }

    /**
     * @return Functionality
     */
    public function create(): Functionality
    {
        return FunctionalityFactory::create();
    }

	/**
	 * @param Functionality $functionality
	 * @param bool $state
	 *
	 * @return Functionality
	 */
    public function update(Functionality $functionality, bool $state): Functionality
    {
        return $functionality->setActive($state);
    }

    /**
     * @param string $name
     *
     * @return bool
     *
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
     * @return Functionality[]
     */
    public function findAllForTable(): array
    {
        return $this->functionalityRepository->findAll();
    }

    /**
     * @return Functionality[]
     */
    public function findAll(): array
    {
        return $this->functionalityRepository->findAll();
    }

    /**
     * @return int
     *
     * @throws NonUniqueResultException
     */
    public function countAll(): int
    {
        return $this->functionalityRepository->countAll();
    }

    /**
     * @return int
     *
     * @throws NonUniqueResultException
     */
    public function countAllForTable(): int
    {
        return $this->countAll();
    }

    /**
     * @param string $name
     *
     * @return Functionality|null
     *
     * @throws NonUniqueResultException
     */
    public function findOneByName(string $name): ?Functionality
    {
        return $this->functionalityRepository->findOneByName($name);
    }
}
