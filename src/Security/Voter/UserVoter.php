<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserVoter.
 */
class UserVoter extends AbstractVoter
{
    const PROFILE = 'app.voters.user.profile';
    const CHANGE_PASSWORD = 'app.voters.user.change_password';
    const LOGOUT = 'app.voters.user.logout';
    const LIST = 'app.voters.user.list';
    const EDIT = 'app.voters.user.edit';
    const ADD = 'app.voters.user.add';
    const VIEW = 'app.voters.user.view';
    const DELETE = 'app.voters.user.delete';
    const DELETE_MULTIPLE = 'app.voters.user.delete_mutiple';

    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [
                self::PROFILE,
                self::CHANGE_PASSWORD,
                self::LOGOUT,
                self::LIST,
                self::EDIT,
                self::ADD,
                self::VIEW,
                self::DELETE,
                self::DELETE_MULTIPLE,
            ])) {
            return false;
        }

        if ($subject instanceof User) {
            return true;
        }

        if (User::class === $subject) {
            return true;
        }

        return false;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::PROFILE:
                return $this->canProfile($subject, $token);
            case self::CHANGE_PASSWORD:
                return $this->canChangePassword($subject, $token);
            case self::LOGOUT:
                return $this->canLogout($subject, $token);
            case self::LIST:
                return $this->canList($subject, $token);
            case self::EDIT:
                return $this->canEdit($subject, $token);
            case self::ADD:
                return $this->canAdd($subject, $token);
            case self::VIEW:
                return $this->canView($subject, $token);
            case self::DELETE:
                return $this->canDelete($subject, $token);
            case self::DELETE_MULTIPLE:
                return $this->canDeleteMultiple($subject, $token);
                break;
        }

        return false;
    }

    /**
     * @param string         $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canProfile(string $subject, TokenInterface $token)
    {
        return true;
    }

    /**
     * @param string         $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canChangePassword(string $subject, TokenInterface $token)
    {
        return true;
    }

    /**
     * @param string         $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canLogout(string $subject, TokenInterface $token)
    {
        return true;
    }

    /**
     * @param string         $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canList(string $subject, TokenInterface $token)
    {
        return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }

    /**
     * @param string         $subject
     * @param TokenInterface $tokens
     *
     * @return bool
     */
    public function canAdd(string $subject, TokenInterface $token)
    {
        return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }

    /**
     * @param User           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canEdit(User $subject, TokenInterface $token)
    {
        return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }

    /**
     * @param User           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canView(User $subject, TokenInterface $token)
    {
        return $this->canList($subject, $token);
    }

    /**
     * @param User           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canDelete(User $subject, TokenInterface $token)
    {
        return $this->canEdit($subject, $token);
    }

    /**
     * @param string         $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canDeleteMultiple(string $subject, TokenInterface $token)
    {
        return $this->canAdd($subject, $token);
    }
}
