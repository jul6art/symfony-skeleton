<?php

namespace App\Twig;

use App\Entity\Functionality;
use App\Entity\Setting;
use App\Entity\User;
use App\Manager\FunctionalityManagerTrait;
use App\Manager\SettingManagerTrait;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class SettingExtension.
 */
class SettingExtension extends AbstractExtension
{
    use SettingManagerTrait;

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('setting', [$this, 'getSetting']),
        ];
    }

	/**
	 * @param string $name
	 *
	 * @return Setting|null
	 * @throws NonUniqueResultException
	 */
    public function getSetting(string $name): ?Setting
    {
        return $this->settingManager->findOneByName($name);
    }
}
