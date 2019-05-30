<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class DefaultVoter
 * @package App\Security\Voter
 */
class DefaultVoter extends AbstractVoter
{
	const ACCESS_PAGE_HOME = 'app.voters.pages.home';

	/**
	 * @param string $attribute
	 * @param mixed $subject
	 *
	 * @return bool
	 */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [
        	    self::ACCESS_PAGE_HOME,
	        ])) {
        	return false;
        }

        return true;
    }

	/**
	 * @param string $attribute
	 * @param mixed $subject
	 * @param TokenInterface $token
	 *
	 * @return bool
	 */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        switch ($attribute) {
	        case self::ACCESS_PAGE_HOME:
	        	return $this->canAccessPageHome($subject, $token);
                break;
        }

        return false;
    }

	/**
	 * @param $subject
	 * @param TokenInterface $token
	 *
	 * @return bool
	 */
    public function canAccessPageHome($subject, TokenInterface $token) {
    	return $this->isConnected($token);
    }
}
