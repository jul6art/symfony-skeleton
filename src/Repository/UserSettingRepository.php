<?php

namespace App\Repository;

use App\Entity\UserSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class UserSettingRepository
 * @package App\Repository
 */
class UserSettingRepository extends ServiceEntityRepository
{
	/**
	 * UserSettingRepository constructor.
	 *
	 * @param RegistryInterface $registry
	 */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserSetting::class);
    }
}
