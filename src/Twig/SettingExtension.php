<?php

namespace App\Twig;

use App\Entity\Setting;
use App\Manager\SettingManagerTrait;
use Doctrine\ORM\NonUniqueResultException;
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
            new TwigFunction('setting_value', [$this, 'getSettingValue']),
            new TwigFunction('settings', [$this, 'getSettings']),
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

	/**
	 * @param string $name
	 * @param string|null $default
	 *
	 * @return string
	 * @throws NonUniqueResultException
	 */
    public function getSettingValue(string $name, string $default  = null): string
    {
        $setting = $this->settingManager->findOneByName($name);

        return $setting === null ? (string) $default : (string) $setting->getValue();
    }

	/**
	 * @return Setting[]
	 */
	public function getSettings(): array
	{
		return $this->settingManager->findAll();
	}
}
