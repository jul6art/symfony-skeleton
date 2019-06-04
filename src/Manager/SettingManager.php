<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/04/2019
 * Time: 13:13.
 */

namespace App\Manager;

use App\Entity\Setting;
use App\Factory\SettingFactory;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class SettingManager.
 */
class SettingManager extends AbstractManager
{
    /**
     * @var SettingRepository
     */
    private $settingRepository;

	/**
	 * SettingManager constructor.
	 *
	 * @param EntityManagerInterface $entityManager
	 */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->settingRepository = $this->entityManager->getRepository(Setting::class);
    }

	/**
	 * @return Setting
	 */
	public function create(): Setting
	{
		return SettingFactory::create();
	}

    /**
     * @return Setting[]
     */
    public function findAllForTable(): array
    {
        return $this->settingRepository->findAll();
    }

    /**
     * @return int
     *
     * @throws NonUniqueResultException
     */
    public function countAll(): int
    {
        return $this->settingRepository->countAll();
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
     * @return Setting|null
     *
     * @throws NonUniqueResultException
     */
    public function findOneByName(string $name): ?Setting
    {
        return $this->settingRepository->findOneByName($name);
    }
}
