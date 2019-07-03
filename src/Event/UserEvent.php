<?php

namespace App\Event;

use App\Entity\User;

/**
 * Class UserEvent.
 */
class UserEvent extends AbstractEvent
{
    public const ADDED = 'event.user.added';
    public const EDITED = 'event.user.edited';
    public const DELETED = 'event.user.deleted';
    /**
     * @var User
     */
    private $user;

    /**
     * UserEvent constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        parent::__construct();
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return self
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
