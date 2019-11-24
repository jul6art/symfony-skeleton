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
 * Class NotifyOnAddedMessage.
 */
class NotifyOnAddedMessage
{
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
     * @var string|null
     */
    private $password;

    /**
     * NotifyUserOnAddedMessage constructor.
     *
     * @param string|null $firstname
     * @param string|null $lastname
     * @param string|null $username
     * @param string|null $email
     * @param string|null $password
     */
    public function __construct(
        string $firstname = null,
        string $lastname = null,
        string $username = null,
        string $email = null,
        string $password = null
    ) {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
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

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}
