<?php

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class User extends BaseUser
{
	const ROLE_ADMIN = 'ROLE_ADMIN';

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;


	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="firstname", type="string", length=50, nullable=true)
	 * @Assert\Length(max="50")
	 */
	protected $firstname;


	/**
	 * @var string|null
	 *
	 * @ORM\Column(name="lastname", type="string", length=80, nullable=true)
	 * @Assert\Length(max="80")
	 */
	protected $lastname;

	/**
	 * @ORM\ManyToMany(targetEntity="App\Entity\Group")
	 * @ORM\JoinTable(name="user_group",
	 *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
	 * )
	 */
	protected $groups;

	/**
	 * @return null|string
	 */
	public function getFirstname(): ?string {
		return $this->firstname;
	}

	/**
	 * @param null|string $firstname
	 *
	 * @return User
	 */
	public function setFirstname( ?string $firstname ): User {
		$this->firstname = $firstname;

		return $this;
	}

	/**
	 * @return null|string
	 */
	public function getLastname(): ?string {
		return $this->lastname;
	}

	/**
	 * @param null|string $lastname
	 *
	 * @return User
	 */
	public function setLastname( ?string $lastname ): User {
		$this->lastname = $lastname;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isAdmin(): bool
	{
		return $this->hasRole(self::ROLE_ADMIN) || $this->hasRole(self::ROLE_SUPER_ADMIN);
	}
}