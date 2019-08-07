<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/04/2019
 * Time: 13:09.
 */

namespace App\Manager;

use App\Entity\Group;
use App\Entity\Setting;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Saacsos\Randomgenerator\Util\RandomGenerator;

/**
 * Class UserManager.
 */
class UserManager extends AbstractManager
{
    use GroupManagerTrait;
    use SettingManagerTrait;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var string
     */
    private $locale;

    /**
     * UserManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param string                 $locale
     */
    public function __construct(EntityManagerInterface $entityManager, string $locale)
    {
        parent::__construct($entityManager);
        $this->userRepository = $entityManager->getRepository(User::class);
        $this->locale = $locale;
    }

    /**
     * @return User
     *
     * @throws NonUniqueResultException
     */
    public function create(): User
    {
        $defaultTheme = $this->settingManager->findOneValueByName(Setting::SETTING_DEFAULT_THEME, Setting::SETTING_DEFAULT_THEME_VALUE);

        return UserFactory::create($this->groupManager, $this->locale, $defaultTheme);
    }

    /**
     * @return User
     *
     * @throws NonUniqueResultException
     */
    public function createAdmin(): User
    {
        $defaultTheme = $this->settingManager->findOneValueByName(Setting::SETTING_DEFAULT_THEME, Setting::SETTING_DEFAULT_THEME_VALUE);

        return UserFactory::createAdmin($this->groupManager, $this->locale, $defaultTheme);
    }

	/**
	 * @param User $user
	 * @param string $locale
	 *
	 * @return UserManager
	 */
    public function updateLocale(user $user, string $locale): self
    {
        $user->setLocale($locale);

        return $this;
    }

	/**
	 * @param User $user
	 * @param string $theme
	 *
	 * @return UserManager
	 */
    public function updateTheme(user $user, string $theme): self
    {
        $user->setTheme($theme);

        return $this;
    }

	/**
	 * @param User $user
	 *
	 * @return UserManager
	 * @throws NonUniqueResultException
	 */
	public function updateSettings(User $user): self
	{
		$user
			->setLocale($this->locale)
			->setTheme($this->settingManager->findOneValueByName(Setting::SETTING_DEFAULT_THEME));

		return $this;
	}

	/**
	 * @param User $user
	 *
	 * @return UserManager
	 * @throws NonUniqueResultException
	 */
	public function updateGroups(User $user): self
	{
		$user->addGroup($this->groupManager->findOneByName(Group::GROUP_NAME_USER));
		$user->addGroup($this->groupManager->findOneByName(Group::GROUP_NAME_ADMIN));
		return $this;
	}

    /**
     * @return string
     */
    public function generatePassword(): string
    {
        $generator = new RandomGenerator();

        return $generator->level(5)->length(User::LENGTH_GENERATED_PASSWORD)->password()->get();
    }

	/**
	 * @param User $user
	 *
	 * @return UserManager
	 */
    public function activate(User $user): self
    {
    	$user->setEnabled(true);

    	return $this;
    }

	/**
	 * @param User $user
	 *
	 * @return UserManager
	 */
    public function deactivate(User $user): self
    {
    	$user->setEnabled(false);

    	return $this;
    }

	/**
	 * @return User[]
	 */
	public function findAll(): array
	{
		return $this->userRepository->findAll();
	}

    /**
     * @return array
     */
    public function findAllForAudit(): array
    {
        $result = [];
        $users = $this->userRepository->findAll();

        array_walk($users, function (User $user) use (&$result) {
            $result[$user->getId()] = $user->getFullname();
        });

        return $result;
    }

    /**
     * @return User[]
     */
    public function findAllForTable(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * @return int
     *
     * @throws NonUniqueResultException
     */
    public function countAll(): int
    {
        return $this->userRepository->countAll();
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
}
