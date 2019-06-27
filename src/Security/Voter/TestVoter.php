<?php

namespace App\Security\Voter;

use App\Entity\Functionality;
use App\Entity\Setting;
use App\Entity\Test;
use App\Manager\FunctionalityManagerTrait;
use App\Manager\SettingManagerTrait;
use DH\DoctrineAuditBundle\Helper\AuditHelper;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class TestVoter.
 */
class TestVoter extends AbstractVoter
{
    use SettingManagerTrait;
    use FunctionalityManagerTrait;

    const ADD = 'app.voters.test.add';
    const AUDIT = 'app.voters.test.audit';
    const DELETE = 'app.voters.test.delete';
    const DELETE_MULTIPLE = 'app.voters.test.delete_mutiple';
    const EDIT = 'app.voters.test.edit';
    const LIST = 'app.voters.test.list';
    const VIEW = 'app.voters.test.view';

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
                self::DELETE,
                self::DELETE_MULTIPLE,
                self::EDIT,
                self::LIST,
                self::VIEW,
            ])) {
            return false;
        }

        if ($subject instanceof Test) {
            return true;
        }

        if (Test::class === $subject) {
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
	    } elseif ($attribute === self::DELETE) {
        	return $this->canDelete($subject, $token);
	    } elseif ($attribute === self::DELETE_MULTIPLE) {
        	return $this->canDeleteMultiple($subject, $token);
	    } elseif ($attribute === self::EDIT) {
        	return $this->canEdit($subject, $token);
	    } elseif ($attribute === self::LIST) {
        	return $this->canList($subject, $token);
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
            AuditHelper::paramToNamespace(Test::class),
            $subject instanceof Test ? $subject->getId() : null,
            1,
            $this->settingManager->findOneValueByName(Setting::SETTING_AUDIT_LIMIT, Setting::SETTING_AUDIT_LIMIT_VALUE)
        ));
    }

    /**
     * @param Test           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canDelete(Test $subject, TokenInterface $token)
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

	/**
	 * @param Test           $subject
	 * @param TokenInterface $token
	 *
	 * @return bool
	 */
	public function canEdit(Test $subject, TokenInterface $token)
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
	 * @param Test           $subject
	 * @param TokenInterface $token
	 *
	 * @return bool
	 */
	public function canView(Test $subject, TokenInterface $token)
	{
		return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
	}
}
