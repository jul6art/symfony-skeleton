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
use App\Entity\Maintenance;
use App\Manager\Traits\FunctionalityManagerAwareTrait;
use App\Manager\Traits\SettingManagerAwareTrait;
use DH\DoctrineAuditBundle\Helper\AuditHelper;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class MaintenanceVoter.
 */
class MaintenanceVoter extends AbstractVoter
{
    use FunctionalityManagerAwareTrait;
    use SettingManagerAwareTrait;

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
        if (!$this->functionalityManager->isActive(FunctionalityName::FUNC_NAME_MAINTENANCE)) {
            return false;
        }

        return !empty($this->auditReader->getAudits(
            AuditHelper::paramToNamespace(Maintenance::class),
            $subject instanceof Maintenance ? $subject->getId() : null,
            1,
            $this->settingManager->findOneValueByName(SettingName::SETTING_NAME_AUDIT_LIMIT, SettingName::SETTING_VALUE_AUDIT_LIMIT)
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
        if (!$this->functionalityManager->isActive(FunctionalityName::FUNC_NAME_MAINTENANCE)) {
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
