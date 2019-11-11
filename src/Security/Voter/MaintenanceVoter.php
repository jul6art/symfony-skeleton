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

    public const AUDIT = 'app.voters.maintenance.audit';
    public const EDIT = 'app.voters.maintenance.edit';
    public const OVERVIEW = 'app.voters.maintenance.overview';

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
                self::AUDIT,
                self::EDIT,
                self::OVERVIEW,
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
        if (self::AUDIT === $attribute) {
            return $this->canAudit($subject, $token);
        } elseif (self::EDIT === $attribute) {
            return $this->canEdit($subject, $token);
        } elseif (self::OVERVIEW === $attribute) {
            return $this->canOverview($subject, $token);
        }

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
        if (!$this->functionalityManager->isActive(Functionality::FUNC_MAINTENANCE)) {
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
     *
     * @throws NonUniqueResultException
     */
    public function canEdit(Maintenance $subject, TokenInterface $token): bool
    {
        if (!$this->functionalityManager->isActive(Functionality::FUNC_MAINTENANCE)) {
            return false;
        }

        return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }

    /**
     * @param Maintenance    $subject
     * @param TokenInterface $token
     *
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function canOverview(Maintenance $subject, TokenInterface $token): bool
    {
        return $this->canEdit($subject, $token);
    }
}
