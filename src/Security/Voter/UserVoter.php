<?php

namespace App\Security\Voter;

use App\Entity\Functionality;
use App\Entity\Setting;
use App\Entity\User;
use App\Manager\FunctionalityManagerTrait;
use App\Manager\SettingManagerTrait;
use DH\DoctrineAuditBundle\Helper\AuditHelper;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserVoter.
 */
class UserVoter extends AbstractVoter
{
	use FunctionalityManagerTrait;
    use SettingManagerTrait;

    const ADD = 'app.voters.user.add';
    const AUDIT = 'app.voters.user.audit';
    const CHANGE_PASSWORD = 'app.voters.user.change_password';
    const DELETE = 'app.voters.user.delete';
    const DELETE_MULTIPLE = 'app.voters.user.delete_mutiple';
    const EDIT = 'app.voters.user.edit';
    const LIST = 'app.voters.user.list';
    const PROFILE = 'app.voters.user.profile';
    const LOGOUT = 'app.voters.user.logout';
    const VIEW = 'app.voters.user.view';

    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (!\in_array($attribute, [
                self::ADD,
                self::AUDIT,
                self::CHANGE_PASSWORD,
                self::DELETE,
                self::DELETE_MULTIPLE,
                self::EDIT,
                self::LIST,
                self::LOGOUT,
                self::PROFILE,
                self::VIEW,
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
        if ($attribute === self::ADD) {
        	return $this->canAdd($subject, $token);
        } elseif ($attribute === self::AUDIT) {
        	return $this->canAudit($subject, $token);
        } elseif ($attribute === self::CHANGE_PASSWORD) {
        	return $this->canChangePassword($subject, $token);
        } elseif ($attribute === self::DELETE) {
        	return $this->canDelete($subject, $token);
        } elseif ($attribute === self::DELETE_MULTIPLE) {
        	return $this->canDeleteMultiple($subject, $token);
        } elseif ($attribute === self::EDIT) {
        	return $this->canEdit($subject, $token);
        } elseif ($attribute === self::LIST) {
        	return $this->canList($subject, $token);
        } elseif ($attribute === self::LOGOUT) {
        	return $this->canLogout($subject, $token);
        } elseif ($attribute === self::PROFILE) {
        	return $this->canProfile($subject, $token);
        } elseif ($attribute === self::VIEW) {
        	return $this->canView($subject, $token);
        }

        return false;
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
     * @param $subject
     * @param TokenInterface $token
     *
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function canAudit($subject, TokenInterface $token)
    {
	    if (!$this->functionalityManager->isActive(Functionality::FUNC_AUDIT)) {
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
     * @param User           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canDelete(User $subject, TokenInterface $token)
    {
    	if ($subject === $token->getUser()) {
    		return  false;
	    }

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
	public function canProfile(string $subject, TokenInterface $token)
	{
		return true;
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
}
