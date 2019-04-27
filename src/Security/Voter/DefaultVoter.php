<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class DefaultVoter
 * @package App\Security\Voter
 */
class DefaultVoter extends Voter
{
	const SWITCH_THEME = 'app.voters.admin.switch_theme';

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
	        ])) {
        	return false;
        }

        if ($subject instanceof User) {
        	return true;
        };

        if ($subject === User::class) {
        	return true;
        };

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
	        case self::SWITCH_THEME:
	        	return $this->canSwicthTheme($subject, $token);
                break;
        }

        return false;
    }

	/**
	 * @param string $subject
	 * @param TokenInterface $token
	 *
	 * @return bool
	 */
    public function canSwicthTheme(string $subject, TokenInterface $token) {
    	return true;
    }
}
