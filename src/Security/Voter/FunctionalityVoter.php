<?php

namespace App\Security\Voter;

use App\Entity\Functionality;
use App\Manager\FunctionalityManager;
use App\Manager\FunctionalityManagerTrait;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class FunctionalityVoter.
 */
class FunctionalityVoter extends AbstractVoter
{
    use FunctionalityManagerTrait;

    const AUDIT = 'app.voters.functionality.audit';
    const CACHE_CLEAR = 'app.voters.functionality.cache_clear';
    const CONFIRM_DELETE = 'app.voters.functionality.confirm_delete';
    const EDIT = 'app.voters.functionality.edit';
    const EDIT_IN_PLACE = 'app.voters.functionality.edit_in_place';
    const MANAGE_FUNCTIONALITIES = 'app.voters.functionality.manage_functionalities';
    const MANAGE_SETTINGS = 'app.voters.functionality.manage_settings';
    const SWITCH_LOCALE = 'app.voters.functionality.switch_locale';
    const SWITCH_THEME = 'app.voters.functionality.switch_theme';
    const WATCH_FORM = 'app.voters.functionality.watch_form';

    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (!\in_array($attribute, [
                self::AUDIT,
                self::CACHE_CLEAR,
                self::CONFIRM_DELETE,
                self::EDIT,
                self::EDIT_IN_PLACE,
                self::MANAGE_FUNCTIONALITIES,
                self::MANAGE_SETTINGS,
                self::SWITCH_LOCALE,
                self::SWITCH_THEME,
                self::WATCH_FORM,
            ])) {
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
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // ... (check conditions and return true to grant permission) ...
	    if ($attribute === self::AUDIT) {
		    return $this->canAudit($subject, $token);
	    } elseif ($attribute === self::CACHE_CLEAR) {
		    return $this->canClearCache($subject, $token);
	    } elseif ($attribute === self::CONFIRM_DELETE) {
		    return $this->canConfirmDelete($subject, $token);
	    } elseif ($attribute === self::EDIT) {
		    return $this->canEdit($subject, $token);
	    } elseif ($attribute === self::EDIT_IN_PLACE) {
		    return $this->canEditInPlace($subject, $token);
	    } elseif ($attribute === self::MANAGE_FUNCTIONALITIES) {
		    return $this->canManageFunctionalities($subject, $token);
	    } elseif ($attribute === self::MANAGE_SETTINGS) {
		    return $this->canManageSettings($subject, $token);
	    } elseif ($attribute === self::SWITCH_LOCALE) {
		    return $this->canSwitchLocale($subject, $token);
	    } elseif ($attribute === self::SWITCH_THEME) {
		    return $this->canSwitchTheme($subject, $token);
	    } elseif ($attribute === self::WATCH_FORM) {
		    return $this->canWatchForm($subject, $token);
	    }

        return false;
    }

    /**
     * @param string               $subject
     * @param TokenInterface       $token
     * @param FunctionalityManager $functionalityManager
     *
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function canAudit(string $subject, TokenInterface $token)
    {
    	if (!$this->isConnected($token)) {
    		return false;
	    }

        return $this->functionalityManager->isActive(Functionality::FUNC_AUDIT);
    }

	/**
	 * @param string               $subject
	 * @param TokenInterface       $token
	 * @param FunctionalityManager $functionalityManager
	 *
	 * @return bool
	 *
	 * @throws NonUniqueResultException
	 */
	public function canClearCache(string $subject, TokenInterface $token)
	{
		if (!$this->isConnected($token)) {
			return false;
		}

		return $this->functionalityManager->isActive(Functionality::FUNC_CLEAR_CACHE);
	}

    /**
     * @param string               $subject
     * @param TokenInterface       $token
     * @param FunctionalityManager $functionalityManager
     *
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function canConfirmDelete(string $subject, TokenInterface $token)
    {
	    if (!$this->isConnected($token)) {
		    return false;
	    }

	    return $this->functionalityManager->isActive(Functionality::FUNC_CONFIRM_DELETE);
    }

	/**
	 * @param Functionality  $subject
	 * @param TokenInterface $token
	 *
	 * @return bool
	 */
	public function canEdit(Functionality $subject, TokenInterface $token)
	{
		if (empty($this->functionalityManager->findAllByConfigured())) {
			return false;
		}

		return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
	}

	/**
	 * @param string               $subject
	 * @param TokenInterface       $token
	 * @param FunctionalityManager $functionalityManager
	 *
	 * @return bool
	 *
	 * @throws NonUniqueResultException
	 */
	public function canEditInPlace(string $subject, TokenInterface $token)
	{
		if (!$this->accessDecisionManager->decide($token, ['ROLE_ADMIN'])) {
			return false;
		}

		return $this->functionalityManager->isActive(Functionality::FUNC_EDIT_IN_PLACE);
	}

	/**
	 * @param string         $subject
	 * @param TokenInterface $token
	 *
	 * @return bool
	 */
	public function canManageFunctionalities(string $subject, TokenInterface $token)
	{
		if (empty($this->functionalityManager->findAllByConfigured())) {
			return false;
		}

		return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
	}

    /**
     * @param string               $subject
     * @param TokenInterface       $token
     * @param FunctionalityManager $functionalityManager
     *
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    public function canManageSettings(string $subject, TokenInterface $token)
    {
	    if (!$this->accessDecisionManager->decide($token, ['ROLE_ADMIN'])) {
	    	return false;
	    }

	    return $this->functionalityManager->isActive(Functionality::FUNC_MANAGE_SETTINGS);
    }

	/**
	 * @param string               $subject
	 * @param TokenInterface       $token
	 * @param FunctionalityManager $functionalityManager
	 *
	 * @return bool
	 *
	 * @throws NonUniqueResultException
	 */
	public function canSwitchLocale(string $subject, TokenInterface $token)
	{
		return $this->functionalityManager->isActive(Functionality::FUNC_SWITCH_LOCALE);
	}

	/**
	 * @param string               $subject
	 * @param TokenInterface       $token
	 * @param FunctionalityManager $functionalityManager
	 *
	 * @return bool
	 *
	 * @throws NonUniqueResultException
	 */
	public function canSwitchTheme(string $subject, TokenInterface $token)
	{
		if (!$this->isConnected($token)) {
			return false;
		}

		return $this->functionalityManager->isActive(Functionality::FUNC_SWITCH_THEME);
	}

	/**
	 * @param string               $subject
	 * @param TokenInterface       $token
	 * @param FunctionalityManager $functionalityManager
	 *
	 * @return bool
	 *
	 * @throws NonUniqueResultException
	 */
	public function canWatchForm(string $subject, TokenInterface $token)
	{
		return $this->functionalityManager->isActive(Functionality::FUNC_FORM_WATCHER);
	}
}
