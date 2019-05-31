<?php

namespace App\Repository;

use App\Entity\UserSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSetting[]    findAll()
 * @method UserSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
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
