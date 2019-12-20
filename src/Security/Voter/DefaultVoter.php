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
use App\Manager\Traits\FunctionalityManagerAwareTrait;
use App\Manager\Traits\MaintenanceManagerAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class DefaultVoter.
 */
class DefaultVoter extends AbstractVoter
{
    use FunctionalityManagerAwareTrait;
    use MaintenanceManagerAwareTrait;

    public const ACCESS_PAGE_HOME = 'app.voters.pages.home';
    public const MAINTENANCE = 'app.voters.maintenance';
    public const TRANSLATE = 'app.voters.translate';

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
                self::ACCESS_PAGE_HOME,
                self::MAINTENANCE,
                self::TRANSLATE,
            ])
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param string         $attribute
     * @param mixed          $subject
     * @param TokenInterface $token
     *
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        if (self::ACCESS_PAGE_HOME === $attribute) {
            return $this->canAccessPageHome($subject, $token);
        } elseif (self::MAINTENANCE === $attribute) {
            return $this->canMaintenance($subject, $token);
        } elseif (self::TRANSLATE === $attribute) {
            return $this->canTranslate($subject, $token);
        }

        return false;
    }

    /**
     * @param $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canAccessPageHome($subject, TokenInterface $token): bool
    {
        return $this->isConnected($token);
    }

    /**
     * @param $subject
     * @param TokenInterface $token
     *
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function canMaintenance($subject, TokenInterface $token): bool
    {
        if (!$this->functionalityManager->isActive(FunctionalityName::FUNC_NAME_MAINTENANCE)) {
            return false;
        }

        $maintenance = $this->maintenanceManager->findOneByLatest();

        if (null === $maintenance) {
            return false;
        }

        return $maintenance->isActive();
    }

    /**
     * @param $subject
     * @param TokenInterface $token
     *
     * @return bool
     *
     * @TODO must appear in access control section of security
     *
     * @throws NonUniqueResultException
     */
    public function canTranslate($subject, TokenInterface $token): bool
    {
        if (!$this->functionalityManager->isActive(FunctionalityName::FUNC_NAME_SWITCH_LOCALE)) {
            return false;
        }

        return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }
}
