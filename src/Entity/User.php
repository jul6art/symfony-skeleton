<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\EntityListeners({"App\EntityListener\UserEntityListener"})
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields="email", repositoryMethod="findByUniqueEmail")
 * @UniqueEntity(fields="username", repositoryMethod="findByUniqueUsername")
 * @method string|null getLocale()
 * @method setLocale(string $locale)
 * @method string|null getTheme()
 * @method setTheme(string $theme)
 */
class User extends BaseUser
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const GENDER_MALE = 'm';
    public const GENDER_FEMALE = 'f';
    public const SETTING_LOCALE = 'locale';
    public const SETTING_THEME = 'theme';
    public const LENGTH_GENERATED_PASSWORD = 8;
    public const DEFAULT_PASSWORD = 'vsweb';
    public const DEFAULT_ADMIN_USERNAME = 'admin';
    public const DEFAULT_USER_USERNAME = 'user';

    use TimestampableEntity;
    use BlameableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=1, nullable=true)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=1,max=1)
     */
    protected $gender;

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
     * @var ArrayCollection|UserSetting[]
     * @ORM\OneToMany(targetEntity="App\Entity\UserSetting", mappedBy="user", cascade={"all"})
     */
    protected $settings;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->settings = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string|null $gender
     *
     * @return User
     */
    public function setGender(?string $gender): User
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string|null $firstname
     *
     * @return User
     */
    public function setFirstname(?string $firstname): User
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string|null $lastname
     *
     * @return User
     */
    public function setLastname(?string $lastname): User
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFullname(): ?string
    {
        return ucwords(sprintf('%s %s', $this->firstname, $this->lastname));
    }

    /**
     * @return ArrayCollection|UserSetting[]
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param UserSetting $setting
     *
     * @return User
     */
    public function addSetting(UserSetting $setting): self
    {
        if (!$this->settings->contains($setting)) {
            $this->settings[] = $setting;
            $setting->setUser($this);
        }

        return $this;
    }

    /**
     * @param UserSetting $setting
     *
     * @return User
     */
    public function removeSetting(UserSetting $setting): self
    {
        if ($this->settings->contains($setting)) {
            $this->settings->removeElement($setting);
            if ($setting->getUser() === $this) {
                $setting->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return UserSetting|null
     */
    public function getSetting(string $name): ? UserSetting
    {
        foreach ($this->settings as $setting) {
            if ($setting->getName() === $name) {
                return $setting;
            }
        }

        return null;
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return self
     */
    public function setSetting(string $name, string $value): self
    {
        $setting = $this->getSetting($name);

        if (null === $setting) {
            $setting = (new UserSetting())
                ->setName($name);

            $this->addSetting($setting);
        }

        $setting->setValue($value);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasSetting(string $name): ? bool
    {
        return null !== $this->getSetting($name);
    }

	/**
	 * @param $name
	 * @param $arguments
	 *
	 * @return null|string
	 */
	public function __call($name, $arguments)
	{
		$property = $this->camelToSnake(substr($name, 3));

		if (0 === strpos($name, 'get')) {
			return $this->$property;
		} elseif (0 === strpos($name, 'set')) {
			$this->$property = $arguments[0];

			return $this;
		}
	}

	private function camelToSnake($input)
	{
		return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
	}

	/**
	 * @param $name
	 * @return string|null
	 */
	public function __get($name)
	{
		if ($this->hasSetting($name)) {
			return $this->getSetting($name)->getValue();
		}

		return null;
	}

	/**
	 * @param string $name
	 * @param string $value
	 * @return UserSetting|null
	 */
	public function __set($name, $value)
	{
		return $this->setSetting($name, $value);
	}

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN) or $this->hasRole(self::ROLE_SUPER_ADMIN);
    }
}
