<?php

namespace App\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`group`")
 */
class Group extends BaseGroup
{
	public const GROUP_NAME_USER = 'user';
	public const GROUP_NAME_ADMIN = 'admin';
	public const GROUP_NAME_SUPER_ADMIN = 'super_admin';

	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
