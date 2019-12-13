<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Entity;

use App\Entity\Traits\BlameableEntityAwareTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FunctionalityRepository")
 * @UniqueEntity(fields={"name"})
 */
class Functionality
{
    use BlameableEntityAwareTrait;
    use TimestampableEntity;

    public const FUNC_AUDIT = 'func.audit';
    public const FUNC_CLEAR_CACHE = 'func.clear_cache';
    public const FUNC_EDIT_IN_PLACE = 'func.edit_in_place';
    public const FUNC_CONFIRM_DELETE = 'func.confirm_delete';
    public const FUNC_FORM_WATCHER = 'func.form_watcher';
    public const FUNC_MAINTENANCE = 'func.maintenance';
    public const FUNC_MANAGE_SETTINGS = 'func.manage_settings';
    public const FUNC_PROGRESSIVE_WEB_APP = 'func.progressive_web_app';
    public const FUNC_SWITCH_LOCALE = 'func.switch_locale';
    public const FUNC_SWITCH_THEME = 'func.switch_theme';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80, unique=true)
     * @Assert\Length(max="80")
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * Functionality constructor.
     */
    public function __construct()
    {
        $this->active = true;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return $this
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
