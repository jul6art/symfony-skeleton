<?php

namespace App\Security\Voter;

use App\Entity\Functionality;
use App\Manager\FunctionalityManager;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class FunctionalityVoter
 * @package App\Security\Voter
 */
class FunctionalityVoter extends AbstractVoter
{
	const SWITCH_THEME = 'app.voters.functionality.switch_theme';
	const SWITCH_LOCALE = 'app.voters.functionality.switch_locale';
	const MANAGE_SETTINGS = 'app.voters.functionality.manage_settings';

	/**
	 * @var FunctionalityManager
	 */
	private $functionalityManager;

	/**
	 * FunctionalityVoter constructor.
	 *
	 * @param AccessDecisionManagerInterface $accessDecisionManager
	 * @param FunctionalityManager $functionalityManager
	 */
	public function __construct( AccessDecisionManagerInterface $accessDecisionManager, FunctionalityManager $functionalityManager) {
		parent::__construct( $accessDecisionManager );
		$this->functionalityManager = $functionalityManager;
	}

	/**
	 * @param string $attribute
	 * @param mixed $subject
	 *
	 * @return bool
	 */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [
        	    self::SWITCH_THEME,
        	    self::SWITCH_LOCALE,
        	    self::MANAGE_SETTINGS,
	        ])) {
        	return false;
        }

        if ($subject instanceof Functionality) {
        	return true;
        };

        if ($subject === Functionality::class) {
        	return true;
        };

        return false;
    }

	/**
	 * @param string $attribute
	 * @param mixed $subject
	 * @param TokenInterface $token
	 *
	 * @return bool
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
	        case self::SWITCH_THEME:
	        	return $this->canSwicthTheme($subject, $token);
                break;
	        case self::SWITCH_LOCALE:
	        	return $this->canSwitchLocale($subject, $token);
                break;
	        case self::MANAGE_SETTINGS:
	        	return $this->canManageSettings($subject, $token);
                break;
        }

        return false;
    }

	/**
	 * @param string $subject
	 * @param TokenInterface $token
	 * @param FunctionalityManager $functionalityManager
	 *
	 * @return bool
	 * @throws NonUniqueResultException
	 */
    public function canSwicthTheme(string $subject, TokenInterface $token) {
    	return $this->functionalityManager->isActive(Functionality::FUNC_SWITCH_THEME);
    }

	/**
	 * @param string $subject
	 * @param TokenInterface $token
	 * @param FunctionalityManager $functionalityManager
	 *
	 * @return bool
	 * @throws NonUniqueResultException
	 */
    public function canSwitchLocale(string $subject, TokenInterface $token) {
	    return $this->functionalityManager->isActive(Functionality::FUNC_SWITCH_LOCALE);
    }

	/**
	 * @param string $subject
	 * @param TokenInterface $token
	 *
	 * @return bool
	 */
    public function canManageSettings(string $subject, TokenInterface $token) {
    	return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }
}