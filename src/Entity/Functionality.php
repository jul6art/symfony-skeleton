<?php

namespace App\Entity;

use App\Entity\Traits\BlameableEntity;
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
    use BlameableEntity;
    use TimestampableEntity;

    public const FUNC_AUDIT = 'func.audit';
    public const FUNC_CLEAR_CACHE = 'func.clear_cache';
    public const FUNC_EDIT_IN_PLACE = 'func.edit_in_place';
    public const FUNC_CONFIRM_DELETE = 'func.confirm_delete';
    public const FUNC_FORM_WATCHER = 'func.form_watcher';
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
