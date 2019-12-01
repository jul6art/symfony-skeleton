<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 24/11/2019
 * Time: 15:30.
 */

namespace App\Message;

/**
 * Class NotifyOnRegistrationMessage.
 */
class NotifyOnRegistrationMessage
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string|null
     */
    private $firstname;

    /**
     * @var string|null
     */
    private $lastname;

    /**
     * @var string|null
     */
    private $username;

    /**
     * @var string|null
     */
    private $email;

    /**
     * NotifyOnRegistrationMessage constructor.
     *
     * @param int         $id
     * @param string|null $firstname
     * @param string|null $lastname
     * @param string|null $username
     * @param string|null $email
     */
    public function __construct(
        int $id,
        string $firstname = null,
        string $lastname = null,
        string $username = null,
        string $email = null
    ) {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->username = $username;
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
}
