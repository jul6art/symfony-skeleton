<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Twig;

use App\Entity\Setting;
use App\Manager\SettingManagerAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class SettingExtension.
 */
class SettingExtension extends AbstractExtension
{
    use SettingManagerAwareTrait;

    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * SettingExtension constructor.
     *
     * @param RequestStack $stack
     */
    public function __construct(RequestStack $stack)
    {
        $this->stack = $stack;
    }

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
     *
     * @throws NonUniqueResultException
     */
    public function getSetting(string $name): ?Setting
    {
        return $this->settingManager->findOneByName($name);
    }

    /**
     * @param string      $name
     * @param string|null $default
     *
     * @return string
     *
     * @throws NonUniqueResultException
     */
    public function getSettingValue(string $name, string $default = null): string
    {
        $request = $this->stack->getMasterRequest();

        if (null === $request or !$request->request->has($name)) {
            $value = $default;
            $setting = $this->settingManager->findOneByName($name);

            if (null !== $setting) {
                $value = $setting->getValue();
            }

            if (null !== $request) {
                $request->request->set($name, $value);
            }

            return $value;
        } else {
            return $request->request->get($name);
        }
    }

    /**
     * @return Setting[]
     */
    public function getSettings(): array
    {
        return $this->settingManager->findAll();
    }
}
