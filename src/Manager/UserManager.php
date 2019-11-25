<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var string
     */
    private $locale;

    /**
     * UserManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface  $tokenStorage
     * @param string                 $locale
     */
    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage, string $locale)
    {
        parent::__construct($entityManager);
        $this->userRepository = $entityManager->getRepository(User::class);
        $this->tokenStorage = $tokenStorage;
        $this->locale = $locale;
    }

    /**
     * @return GroupManager
     */
    public function getGroupManager(): GroupManager
    {
        return $this->groupManager;
    }

    /**
     * @return User
     *
     * @throws NonUniqueResultException
     */
    public function create(): User
    {
        $defaultTheme = $this->settingManager->findOneValueByName(Setting::SETTING_DEFAULT_THEME, Setting::SETTING_DEFAULT_THEME_VALUE);

        return UserFactory::build($this->groupManager, Group::GROUP_NAME_USER, $this->locale, $defaultTheme);
    }

    /**
     * @return User
     *
     * @throws NonUniqueResultException
     */
    public function createAdmin(): User
    {
        $defaultTheme = $this->settingManager->findOneValueByName(Setting::SETTING_DEFAULT_THEME, Setting::SETTING_DEFAULT_THEME_VALUE);

        return UserFactory::build($this->groupManager, Group::GROUP_NAME_ADMIN, $this->locale, $defaultTheme);
    }

    /**
     * @return User
     *
     * @throws NonUniqueResultException
     */
    public function createSuperAdmin(): User
    {
        $defaultTheme = $this->settingManager->findOneValueByName(Setting::SETTING_DEFAULT_THEME, Setting::SETTING_DEFAULT_THEME_VALUE);

        return UserFactory::build($this->groupManager, Group::GROUP_NAME_SUPER_ADMIN, $this->locale, $defaultTheme);
    }

    /**
     * @param User   $user
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
     * @param User   $user
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
     * @param User        $user
     * @param string|null $locale
     *
     * @return $this
     *
     * @throws NonUniqueResultException
     */
    public function presetSettings(User $user, string $locale = null): self
    {
        $user
            ->setLocale($locale ?? $this->locale)
            ->setTheme($this->settingManager->findOneValueByName(Setting::SETTING_DEFAULT_THEME));

        return $this;
    }

    /**
     * @param User $user
     *
     * @return UserManager
     *
     * @throws NonUniqueResultException
     */
    public function presetGroups(User $user): self
    {
        $user->addGroup($this->groupManager->findOneByName(Group::GROUP_NAME_USER));

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
     * @param User $user
     *
     * @return UserManager
     */
    public function logout(): self
    {
        $this->tokenStorage->setToken(null);

        return $this;
    }

    /**
     * @return User[]
     */
    public function findByGroup(Group $group): array
    {
        return $this->userRepository->findByGroup($group);
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
