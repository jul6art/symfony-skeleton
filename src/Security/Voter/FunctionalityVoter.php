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
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class FunctionalityVoter.
 */
class FunctionalityVoter extends AbstractVoter
{
    use FunctionalityManagerAwareTrait;

    public const AUDIT = 'app.voters.functionality.audit';
    public const CACHE_CLEAR = 'app.voters.functionality.cache_clear';
    public const CONFIRM_DELETE = 'app.voters.functionality.confirm_delete';
    public const EDIT = 'app.voters.functionality.edit';
    public const EDIT_IN_PLACE = 'app.voters.functionality.edit_in_place';
    public const MAINTENANCE = 'app.voters.functionality.maintenance';
    public const MANAGE_FUNCTIONALITIES = 'app.voters.functionality.manage_functionalities';
    public const MANAGE_SETTINGS = 'app.voters.functionality.manage_settings';
    public const PROGRESSIVE_WEB_APP = 'app.voters.functionality.progressive_web_app';
    public const SWITCH_LOCALE = 'app.voters.functionality.switch_locale';
    public const SWITCH_THEME = 'app.voters.functionality.switch_theme';
    public const WATCH_FORM = 'app.voters.functionality.watch_form';

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
                self::CACHE_CLEAR,
                self::CONFIRM_DELETE,
                self::EDIT,
                self::EDIT_IN_PLACE,
                self::MAINTENANCE,
                self::MANAGE_FUNCTIONALITIES,
                self::MANAGE_SETTINGS,
                self::PROGRESSIVE_WEB_APP,
                self::SWITCH_LOCALE,
                self::SWITCH_THEME,
                self::WATCH_FORM,
            ])
        ) {
            return false;
        }

        if ($subject instanceof Functionality) {
            return true;
        }

        if (Functionality::class === $subject) {
            return true;
        }

        return false;
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
        // ... (check conditions and return true to grant permission) ...
        if (self::AUDIT === $attribute) {
            return $this->canAudit($subject, $token);
        } elseif (self::CACHE_CLEAR === $attribute) {
            return $this->canClearCache($subject, $token);
        } elseif (self::CONFIRM_DELETE === $attribute) {
            return $this->canConfirmDelete($subject, $token);
        } elseif (self::EDIT === $attribute) {
            return $this->canEdit($subject, $token);
        } elseif (self::EDIT_IN_PLACE === $attribute) {
            return $this->canEditInPlace($subject, $token);
        } elseif (self::MAINTENANCE === $attribute) {
            return $this->canMaintenance($subject, $token);
        } elseif (self::MANAGE_FUNCTIONALITIES === $attribute) {
            return $this->canManageFunctionalities($subject, $token);
        } elseif (self::MANAGE_SETTINGS === $attribute) {
            return $this->canManageSettings($subject, $token);
        } elseif (self::PROGRESSIVE_WEB_APP === $attribute) {
            return $this->canProgressiveWebApp($subject, $token);
        } elseif (self::SWITCH_LOCALE === $attribute) {
            return $this->canSwitchLocale($subject, $token);
        } elseif (self::SWITCH_THEME === $attribute) {
            return $this->canSwitchTheme($subject, $token);
        } elseif (self::WATCH_FORM === $attribute) {
            return $this->canWatchForm($subject, $token);
        }

        return false;
    }

    /**
     * @param string $subject
     * @param TokenInterface $token
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function canAudit(string $subject, TokenInterface $token): bool
    {
        if (!$this->isConnected($token)) {
            return false;
        }

        return $this->functionalityManager->isActive(FunctionalityName::FUNC_NAME_AUDIT);
    }

    /**
     * @param string $subject
     * @param TokenInterface $token
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function canClearCache(string $subject, TokenInterface $token): bool
    {
        if (!$this->accessDecisionManager->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }

        return $this->functionalityManager->isActive(FunctionalityName::FUNC_NAME_CLEAR_CACHE);
    }

    /**
     * @param string $subject
     * @param TokenInterface $token
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function canConfirmDelete(string $subject, TokenInterface $token): bool
    {
        if (!$this->isConnected($token)) {
            return false;
        }

        return $this->functionalityManager->isActive(FunctionalityName::FUNC_NAME_CONFIRM_DELETE);
    }

    /**
     * @param Functionality  $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canEdit(Functionality $subject, TokenInterface $token): bool
    {
        if (empty($this->functionalityManager->findAllByConfigured())) {
            return false;
        }

        return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }

    /**
     * @param string $subject
     * @param TokenInterface $token
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function canEditInPlace(string $subject, TokenInterface $token): bool
    {
        if (!$this->accessDecisionManager->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }

        return $this->functionalityManager->isActive(FunctionalityName::FUNC_NAME_EDIT_IN_PLACE);
    }

    /**
     * @param string         $subject
     * @param TokenInterface $token
     *
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function canMaintenance(string $subject, TokenInterface $token): bool
    {
        if (!$this->accessDecisionManager->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }

        return $this->functionalityManager->isActive(FunctionalityName::FUNC_NAME_MAINTENANCE);
    }

    /**
     * @param string         $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canManageFunctionalities(string $subject, TokenInterface $token): bool
    {
        if (empty($this->functionalityManager->findAllByConfigured())) {
            return false;
        }

        return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }

    /**
     * @param string $subject
     * @param TokenInterface $token
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function canManageSettings(string $subject, TokenInterface $token): bool
    {
        if (!$this->accessDecisionManager->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }

        return $this->functionalityManager->isActive(FunctionalityName::FUNC_NAME_MANAGE_SETTINGS);
    }

    /**
     * @param string $subject
     * @param TokenInterface $token
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function canProgressiveWebApp(string $subject, TokenInterface $token): bool
    {
        if (!$this->isConnected($token)) {
            return false;
        }

        return $this->functionalityManager->isActive(FunctionalityName::FUNC_NAME_PROGRESSIVE_WEB_APP);
    }

    /**
     * @param string $subject
     * @param TokenInterface $token
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function canSwitchLocale(string $subject, TokenInterface $token): bool
    {
        return $this->functionalityManager->isActive(FunctionalityName::FUNC_NAME_SWITCH_LOCALE);
    }

    /**
     * @param string $subject
     * @param TokenInterface $token
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function canSwitchTheme(string $subject, TokenInterface $token): bool
    {
        if (!$this->isConnected($token)) {
            return false;
        }

        return $this->functionalityManager->isActive(FunctionalityName::FUNC_NAME_SWITCH_THEME);
    }

    /**
     * @param string $subject
     * @param TokenInterface $token
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function canWatchForm(string $subject, TokenInterface $token): bool
    {
        return $this->functionalityManager->isActive(FunctionalityName::FUNC_NAME_FORM_WATCHER);
    }
}
