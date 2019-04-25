<?php

namespace App\Security\Voter;

use App\Entity\Test;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class TestVoter
 * @package App\Security\Voter
 */
class TestVoter extends Voter
{
	const LIST = 'app.voters.test.list';
	const EDIT = 'app.voters.test.edit';
	const ADD = 'app.voters.test.add';
	const DELETE = 'app.voters.test.delete';

	/**
	 * @param string $attribute
	 * @param mixed $subject
	 *
	 * @return bool
	 */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [
        	    self::LIST,
        	    self::EDIT,
        	    self::ADD,
        	    self::DELETE,
	        ])) {
        	return false;
        }

        if ($subject instanceof Test) {
        	return true;
        };

        if ($subject === Test::class) {
        	return true;
        };
    }


    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
//        if (!$user instanceof UserInterface) {
//            return false;
//        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
	        case self::LIST:
	        	return $this->canList($subject, $token);
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
    public function canList(string $subject, TokenInterface $token) {
    	return true;
    }
}
