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
use Symfony\Component\HttpFoundation\RequestStack;

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
     * @var RequestStack
     */
    private $stack;

    /**
     * @var array
     */
    private $available_functionalities;

    /**
     * FunctionalityManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param array                  $available_functionalities
     */
    public function __construct(EntityManagerInterface $entityManager, RequestStack $stack, array $available_functionalities)
    {
        parent::__construct($entityManager);
        $this->functionalityRepository = $this->entityManager->getRepository(Functionality::class);
        $this->stack = $stack;
        $this->available_functionalities = $available_functionalities;
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
	 * @return FunctionalityManager
	 */
    public function update(Functionality $functionality, bool $state): self
    {
        $functionality->setActive($state);

        return $this;
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
        $request = $this->stack->getMasterRequest();

        if (!$request->request->has($name)) {
            $functionality = $this->findOneByName($name);

            $state = false;
            if (null !== $functionality) {
                if ($this->isConfigured($functionality)) {
                    $state = $functionality->isActive();
                }
            }

            $request->request->set($name, $state ? 1 : 0);

            return $state;
        } else {
            return (bool) $request->request->get($name);
        }
    }

    /**
     * @param Functionality $functionality
     *
     * @return bool
     */
    public function isConfigured(Functionality $functionality): bool
    {
        return \in_array($functionality->getName(), $this->available_functionalities);
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
     * @return Functionality[]
     */
    public function findAllByConfigured(): array
    {
        $functionalities = $this->functionalityRepository->findAll();

        return array_filter($functionalities, function (Functionality $functionality) {
            return $this->isConfigured($functionality);
        });
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
     * @return int
     *
     * @throws NonUniqueResultException
     */
    public function countAllByConfigured(): int
    {
        return \count($this->findAllByConfigured());
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
