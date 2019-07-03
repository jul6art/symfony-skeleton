<?php

namespace App\Entity;

use App\Entity\Traits\BlameableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Class Setting.
 *
 * @ORM\Entity(repositoryClass="App\Repository\SettingRepository")
 */
class Setting
{
    public const SETTING_PROJECT_NAME = 'setting.project_name';
    public const SETTING_BASE_TITLE = 'setting.base_title';
    public const SETTING_DEFAULT_THEME = 'setting.default_theme';
    public const SETTING_AUDIT_LIMIT = 'setting.audit_limit';
    public const SETTING_TOASTR_VERTICAL_POSITION = 'setting.toastr_vertical_position';
    public const SETTING_TOASTR_HORIZONTAL_POSITION = 'setting.toastr_horizontal_position';

    public const SETTING_PROJECT_NAME_VALUE = 'Symfony skeleton';
    public const SETTING_BASE_TITLE_VALUE = ' | Symfony Skeleton';
    public const SETTING_DEFAULT_THEME_VALUE = 'red';
    public const SETTING_AUDIT_LIMIT_VALUE = 200;
    public const SETTING_TOASTR_VERTICAL_POSITION_VALUE = 'bottom';
    public const SETTING_TOASTR_HORIZONTAL_POSITION_VALUE = 'center';

    use TimestampableEntity;
    use BlameableEntity;

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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
