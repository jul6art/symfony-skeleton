<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Security\Voter;

use App\Entity\Constants\FunctionalityName;
use App\Entity\Functionality;
use App\Entity\Setting;
use App\Entity\User;
use App\Manager\FunctionalityManagerAwareTrait;
use App\Manager\SettingManagerAwareTrait;
use DH\DoctrineAuditBundle\Helper\AuditHelper;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserVoter.
 */
class UserVoter extends AbstractVoter
{
    use FunctionalityManagerAwareTrait;
    use SettingManagerAwareTrait;

    public const ADD = 'app.voters.user.add';
    public const AUDIT = 'app.voters.user.audit';
    public const CHANGE_PASSWORD = 'app.voters.user.change_password';
    public const CHANGE_AVATAR = 'app.voters.user.change_avatar';
    public const DELETE = 'app.voters.user.delete';
    public const SELF_DELETE = 'app.voters.user.self_delete';
    public const DELETE_MULTIPLE = 'app.voters.user.delete_mutiple';
    public const EDIT = 'app.voters.user.edit';
    public const LIST = 'app.voters.user.list';
    public const PROFILE = 'app.voters.user.profile';
    public const LOGOUT = 'app.voters.user.logout';
    public const VIEW = 'app.voters.user.view';
    public const IMPERSONATE = 'app.voters.user.impersonate';

    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        if (
            !\in_array($attribute, [
                self::ADD,
                self::AUDIT,
                self::CHANGE_AVATAR,
                self::CHANGE_PASSWORD,
                self::SELF_DELETE,
                self::DELETE,
                self::DELETE_MULTIPLE,
                self::EDIT,
                self::LIST,
                self::LOGOUT,
                self::PROFILE,
                self::VIEW,
                self::IMPERSONATE,
            ])
        ) {
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

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        if (self::ADD === $attribute) {
            return $this->canAdd($subject, $token);
        } elseif (self::AUDIT === $attribute) {
            return $this->canAudit($subject, $token);
        } elseif (self::CHANGE_AVATAR === $attribute) {
            return $this->canChangeAvatar($subject, $token);
        } elseif (self::CHANGE_PASSWORD === $attribute) {
            return $this->canChangePassword($subject, $token);
        } elseif (self::SELF_DELETE === $attribute) {
            return $this->canSelfDelete($subject, $token);
        } elseif (self::DELETE === $attribute) {
            return $this->canDelete($subject, $token);
        } elseif (self::DELETE_MULTIPLE === $attribute) {
            return $this->canDeleteMultiple($subject, $token);
        } elseif (self::EDIT === $attribute) {
            return $this->canEdit($subject, $token);
        } elseif (self::LIST === $attribute) {
            return $this->canList($subject, $token);
        } elseif (self::LOGOUT === $attribute) {
            return $this->canLogout($subject, $token);
        } elseif (self::PROFILE === $attribute) {
            return $this->canProfile($subject, $token);
        } elseif (self::VIEW === $attribute) {
            return $this->canView($subject, $token);
        } elseif (self::IMPERSONATE === $attribute) {
            return $this->canImpersonate($subject, $token);
        }

        return false;
    }

    /**
     * @param string         $subject
     * @param TokenInterface $tokens
     *
     * @return bool
     */
    public function canAdd(string $subject, TokenInterface $token): bool
    {
        return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }

    /**
     * @param $subject
     * @param TokenInterface $token
     *
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function canAudit($subject, TokenInterface $token): bool
    {
        if (!$this->functionalityManager->isActive(FunctionalityName::FUNC_NAME_AUDIT)) {
            return false;
        }

        return !empty($this->auditReader->getAudits(
            AuditHelper::paramToNamespace(User::class),
            $subject instanceof User ? $subject->getId() : null,
            1,
            $this->settingManager->findOneValueByName(Setting::SETTING_AUDIT_LIMIT, Setting::SETTING_AUDIT_LIMIT_VALUE)
        ));
    }

    /**
     * @param User           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canChangeAvatar(User $subject, TokenInterface $token): bool
    {
        if ($subject === $token->getUser()) {
            return true;
        }

        return $this->canEdit($subject, $token);
    }

    /**
     * @param string         $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canChangePassword(string $subject, TokenInterface $token): bool
    {
        return true;
    }

    /**
     * @param User           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canDelete(User $subject, TokenInterface $token): bool
    {
        if ($subject === $token->getUser()) {
            return  false;
        }

        return $this->canEdit($subject, $token);
    }

    /**
     * @param User           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canSelfDelete(User $subject, TokenInterface $token): bool
    {
        return  $subject === $token->getUser();
    }

    /**
     * @param string         $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canDeleteMultiple(string $subject, TokenInterface $token): bool
    {
        return $this->canAdd($subject, $token);
    }

    /**
     * @param User           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canEdit(User $subject, TokenInterface $token): bool
    {
        return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }

    /**
     * @param string         $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canList(string $subject, TokenInterface $token): bool
    {
        return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }

    /**
     * @param string         $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canLogout(string $subject, TokenInterface $token): bool
    {
        return true;
    }

    /**
     * @param string         $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canProfile(string $subject, TokenInterface $token): bool
    {
        return true;
    }

    /**
     * @param User           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canView(User $subject, TokenInterface $token): bool
    {
        return $this->canList($subject, $token);
    }

    /**
     * @param User           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canImpersonate(User $subject, TokenInterface $token): bool
    {
        if ($subject === $token->getUser()) {
            return false;
        }

        if (!$subject->isEnabled()) {
            return false;
        }

        if (!$this->accessDecisionManager->decide($token, ['ROLE_ALLOWED_TO_SWITCH'])) {
            return false;
        }

        if ($this->accessDecisionManager->decide($token, ['ROLE_PREVIOUS_ADMIN'])) {
            return false;
        }

        $userToken = new UsernamePasswordToken($subject, null, 'main', $subject->getRoles());

        if ($this->accessDecisionManager->decide($userToken, ['ROLE_ALLOWED_TO_SWITCH'])) {
            return false;
        }

        return true;
    }
}
