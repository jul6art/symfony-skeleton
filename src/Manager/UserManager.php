<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/04/2019
 * Time: 13:09.
 */

namespace App\Manager;

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

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var string
     */
    private $default_theme;

    /**
     * @var string
     */
    private $locale;

    /**
     * UserManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param string                 $default_theme
     * @param string                 $locale
     */
    public function __construct(EntityManagerInterface $entityManager, string $default_theme, string $locale)
    {
        parent::__construct($entityManager);
        $this->userRepository = $entityManager->getRepository(User::class);
        $this->default_theme = $default_theme;
        $this->locale = $locale;
    }

    /**
     * @return User
     *
     * @throws NonUniqueResultException
     */
    public function create(): User
    {
        return UserFactory::create($this->groupManager, $this->locale, $this->default_theme);
    }

    /**
     * @return User
     *
     * @throws NonUniqueResultException
     */
    public function createAdmin(): User
    {
        return UserFactory::createAdmin($this->groupManager, $this->locale, $this->default_theme);
    }

	/**
	 * @param User $user
	 * @param string $locale
	 *
	 * @return bool
	 */
    public function updateLocale(user $user, string $locale): bool
    {
    	$user->setLocale($locale);

    	return $this->save($user);
    }

	/**
	 * @param User $user
	 * @param string $locale
	 *
	 * @return bool
	 */
    public function updateTheme(user $user, string $theme): bool
    {
    	$user->setTheme($theme);

    	return $this->save($user);
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
