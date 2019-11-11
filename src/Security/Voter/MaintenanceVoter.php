<?php

namespace App\Security\Voter;

use App\Entity\Functionality;
use App\Entity\Maintenance;
use App\Entity\Setting;
use App\Manager\FunctionalityManagerTrait;
use App\Manager\SettingManagerTrait;
use DH\DoctrineAuditBundle\Helper\AuditHelper;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class MaintenanceVoter.
 */
class MaintenanceVoter extends AbstractVoter
{
    use FunctionalityManagerTrait;
    use SettingManagerTrait;

    public const ADD = 'app.voters.maintenance.add';
    public const AUDIT = 'app.voters.maintenance.audit';
    public const DELETE = 'app.voters.maintenance.delete';
    public const DELETE_MULTIPLE = 'app.voters.maintenance.delete_mutiple';
    public const EDIT = 'app.voters.maintenance.edit';
    public const LIST = 'app.voters.maintenance.list';
    public const VIEW = 'app.voters.maintenance.view';

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

        if ($subject instanceof Maintenance) {
            return true;
        }

        if (Maintenance::class === $subject) {
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
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canAdd(string $subject, TokenInterface $token): bool
    {
        return false;
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
        if (!$this->functionalityManager->isActive(Functionality::FUNC_AUDIT)) {
            return false;
        }

        return !empty($this->auditReader->getAudits(
            AuditHelper::paramToNamespace(Maintenance::class),
            $subject instanceof Maintenance ? $subject->getId() : null,
            1,
            $this->settingManager->findOneValueByName(Setting::SETTING_AUDIT_LIMIT, Setting::SETTING_AUDIT_LIMIT_VALUE)
        ));
    }

    /**
     * @param Maintenance    $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canDelete(Maintenance $subject, TokenInterface $token): bool
    {
        return false;
    }

    /**
     * @param string         $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canDeleteMultiple(string $subject, TokenInterface $token): bool
    {
        return false;
    }

    /**
     * @param Maintenance    $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canEdit(Maintenance $subject, TokenInterface $token): bool
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
        return false;
    }

    /**
     * @param Maintenance    $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canView(Maintenance $subject, TokenInterface $token): bool
    {
        return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }
}
