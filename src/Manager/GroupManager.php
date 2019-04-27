<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/04/2019
 * Time: 13:13
 */

namespace App\Manager;
use App\Entity\Group;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class GroupManager
 * @package App\Manager
 */
class GroupManager {
	/**
	 * @var GroupRepository
	 */
	private $groupRepository;

	/**
	 * GroupManager constructor.
	 *
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->groupRepository = $entityManager->getRepository(Group::class);
	}

	/**
	 * @param string $name
	 *
	 * @return Group|null
	 * @throws NonUniqueResultException
	 */
	public function findOneByName(string $name)
	{
		return $this->groupRepository->findOneByName($name);
	}
}