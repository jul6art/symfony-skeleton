<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserSetting.
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserSettingRepository")
 */
class UserSetting
{
	/**
	 * @var int
	 *
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $value;

	/**
	 * @var User|null
	 *
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="settings")
	 */
	protected $user = null;

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param string $name
	 *
	 * @return UserSetting
	 */
	public function setName( string $name ): UserSetting {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getValue(): string {
		return $this->value;
	}

	/**
	 * @param string $value
	 *
	 * @return UserSetting
	 */
	public function setValue( string $value ): UserSetting {
		$this->value = $value;

		return $this;
	}

	/**
	 * @return User|null
	 */
	public function getUser(): ?User {
		return $this->user;
	}

	/**
	 * @param User|null $user
	 *
	 * @return UserSetting
	 */
	public function setUser( ?User $user ): UserSetting {
		$this->user = $user;

		return $this;
	}
}