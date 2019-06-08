<?php

namespace App\Security\Voter;

use App\Entity\Setting;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class SettingVoter.
 */
class SettingVoter extends AbstractVoter
{
	const EDIT = 'app.voters.setting.edit';

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
		])) {
			return false;
		}

		if ($subject instanceof Setting) {
			return true;
		}

		if (Setting::class === $subject) {
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
		}

		return false;
	}

	/**
	 * @param Setting         $subject
	 * @param TokenInterface $token
	 *
	 * @return bool
	 */
	public function canEdit(Setting $subject, TokenInterface $token)
	{
		return $this->accessDecisionManager->decide($token, ['ROLE_ADMIN']);
	}
}
