<?php

namespace App\Security\Voter;

use App\Entity\Test;
use DH\DoctrineAuditBundle\Helper\AuditHelper;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class TestVoter.
 */
class TestVoter extends AbstractVoter
{
    const LIST = 'app.voters.test.list';
    const EDIT = 'app.voters.test.edit';
    const ADD = 'app.voters.test.add';
    const VIEW = 'app.voters.test.view';
    const AUDIT = 'app.voters.test.audit';
    const DELETE = 'app.voters.test.delete';
    const DELETE_MULTIPLE = 'app.voters.test.delete_mutiple';

    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [
                self::LIST,
                self::EDIT,
                self::ADD,
                self::VIEW,
                self::AUDIT,
                self::DELETE,
                self::DELETE_MULTIPLE,
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
        switch ($attribute) {
            case self::LIST:
                return $this->canList($subject, $token);
            case self::EDIT:
                return $this->canEdit($subject, $token);
            case self::ADD:
                return $this->canAdd($subject, $token);
            case self::VIEW:
                return $this->canView($subject, $token);
            case self::AUDIT:
                return $this->canAudit($subject, $token);
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
     * @param Test           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canView(Test $subject, TokenInterface $token)
    {
        return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }

    /**
     * @param $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canAudit($subject, TokenInterface $token)
    {
        if (!$this->accessDecisionManager->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }

        $auditEntity = AuditHelper::paramToNamespace(Test::class);

        $id = $subject instanceof Test ? $subject->getId() : null;

        $audits = $this->auditReader->getAudits($auditEntity, $id, 1, $this->audit_limit);

        return !empty($audits);
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
}
