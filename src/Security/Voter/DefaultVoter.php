<?php

namespace App\Security\Voter;

use App\Entity\Functionality;
use App\Entity\User;
use App\Manager\FunctionalityManager;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class DefaultVoter
 * @package App\Security\Voter
 */
class DefaultVoter extends AbstractVoter
{
	const MANAGE_SETTINGS = 'app.voters.admin.manage_settings';

	/**
	 * @param string $attribute
	 * @param mixed $subject
	 *
	 * @return bool
	 */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [
        	    self::MANAGE_SETTINGS,
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
	        case self::MANAGE_SETTINGS:
	        	return $this->canManageSettings($subject, $token);
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
    public function canManageSettings(string $subject, TokenInterface $token) {
    	return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
    }
}
