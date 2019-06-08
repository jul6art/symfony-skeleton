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

    const EDIT = 'app.voters.functionality.edit';
    const SWITCH_THEME = 'app.voters.functionality.switch_theme';
    const SWITCH_LOCALE = 'app.voters.functionality.switch_locale';
    const CACHE_CLEAR = 'app.voters.functionality.cache_clear';
    const MANAGE_SETTINGS = 'app.voters.functionality.manage_settings';
    const MANAGE_FUNCTIONALITIES = 'app.voters.functionality.manage_functionalities';

    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [
                self::EDIT,
                self::SWITCH_THEME,
                self::SWITCH_LOCALE,
                self::CACHE_CLEAR,
                self::MANAGE_SETTINGS,
                self::MANAGE_FUNCTIONALITIES,
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
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
	        case self::EDIT:
                return $this->canEdit($subject, $token);
                break;
            case self::SWITCH_THEME:
                return $this->canSwicthTheme($subject, $token);
                break;
            case self::SWITCH_LOCALE:
                return $this->canSwitchLocale($subject, $token);
                break;
            case self::CACHE_CLEAR:
                return $this->canClearCache($subject, $token);
                break;
            case self::MANAGE_SETTINGS:
                return $this->canManageSettings($subject, $token);
                break;
            case self::MANAGE_FUNCTIONALITIES:
                return $this->canManageFunctionalities($subject, $token);
                break;
        }

        return false;
    }

	/**
	 * @param Functionality $subject
	 * @param TokenInterface $token
	 *
	 * @return bool
	 */
    public function canEdit(Functionality $subject, TokenInterface $token)
    {
	    $functionalities = $this->functionalityManager->findAllByConfigured();

	    if (empty($functionalities)) {
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
    public function canSwicthTheme(string $subject, TokenInterface $token)
    {
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
    public function canClearCache(string $subject, TokenInterface $token)
    {
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
    public function canManageSettings(string $subject, TokenInterface $token)
    {
        return $this->functionalityManager->isActive(Functionality::FUNC_MANAGE_SETTINGS);
    }

    /**
     * @param string         $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function canManageFunctionalities(string $subject, TokenInterface $token)
    {
    	$functionalities = $this->functionalityManager->findAllByConfigured();

    	if (empty($functionalities)) {
    		return false;
	    }

        return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }
}
