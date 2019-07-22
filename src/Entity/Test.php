<?php

namespace App\Entity;

use App\Entity\Traits\BlameableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TestRepository")
 * @ORM\EntityListeners({"App\EntityListener\TestEntityListener"})
 */
class Test
{
    use BlameableEntity;
    use TimestampableEntity;

    public const TEXT_LENGTH = 15;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=Test::TEXT_LENGTH)
     *
     * @ORM\Column(type="text")
     */
    private $content;

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
     * @return Test
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

	/**
	 * @return string|null
	 */
	public function getContent(): ?string
	{
		return $this->content;
	}

	/**
	 * @param string $content
	 *
	 * @return Test
	 */
	public function setContent( string $content ): self
	{
		$this->content = $content;

		return $this;
	}
}
