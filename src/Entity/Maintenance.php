<?php

namespace App\Entity;

use App\Entity\Traits\BlameableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MaintenanceRepository")
 * @ORM\EntityListeners({"App\EntityListener\MaintenanceEntityListener"})
 */
class Maintenance
{
    use TimestampableEntity;
    use BlameableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $exceptionIpList;

    /**
     * Maintenance constructor.
     */
    public function __construct()
    {
        $this->active = false;
        $this->exceptionIpList = [];
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return Maintenance
     */
    public function setActive(bool $active): Maintenance
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getExceptionIpList(): ?array
    {
        return $this->exceptionIpList;
    }

    /**
     * @param array|null $exceptionIpList
     *
     * @return $this
     */
    public function setExceptionIpList(?array $exceptionIpList): self
    {
        $this->exceptionIpList = $exceptionIpList;

        return $this;
    }
}
