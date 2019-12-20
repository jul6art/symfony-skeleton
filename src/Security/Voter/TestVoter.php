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
use App\Entity\Constants\SettingName;
use App\Entity\Test;
use App\Manager\Traits\FunctionalityManagerAwareTrait;
use App\Manager\Traits\SettingManagerAwareTrait;
use DH\DoctrineAuditBundle\Helper\AuditHelper;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class TestVoter.
 */
class TestVoter extends AbstractVoter
{
    use FunctionalityManagerAwareTrait;
    use SettingManagerAwareTrait;

    public const ADD = 'app.voters.test.add';
    public const AUDIT = 'app.voters.test.audit';
    public const DELETE = 'app.voters.test.delete';
    public const DELETE_MULTIPLE = 'app.voters.test.delete_mutiple';
    public const EDIT = 'app.voters.test.edit';
    public const LIST = 'app.voters.test.list';
    public const VIEW = 'app.voters.test.view';

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
                self::DELETE,
                self::DELETE_MULTIPLE,
                self::EDIT,
                self::LIST,
                self::VIEW,
            ])
        ) {
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
        } elseif (self::DELETE === $attribute) {
            return $this->canDelete($subject, $token);
        } elseif (self::DELETE_MULTIPLE === $attribute) {
            return $this->canDeleteMultiple($subject, $token);
        } elseif (self::EDIT === $attribute) {
            return $this->canEdit($subject, $token);
        } elseif (self::LIST === $attribute) {
            return $this->canList($subject, $token);
        } elseif (self::VIEW === $attribute) {
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
            AuditHelper::paramToNamespace(Test::class),
            $subject instanceof Test ? $subject->getId() : null,
            1,
            $this->settingManager->findOneValueByName(SettingName::SETTING_NAME_AUDIT_LIMIT, SettingName::SETTING_VALUE_AUDIT_LIMIT)
        ));
    }

    /**
     * @param Test           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canDelete(Test $subject, TokenInterface $token): bool
    {
        return $this->canEdit($subject, $token);
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
     * @param Test           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canEdit(Test $subject, TokenInterface $token): bool
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
     * @param Test           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canView(Test $subject, TokenInterface $token): bool
    {
        return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }
}
