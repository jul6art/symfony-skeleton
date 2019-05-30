<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 21/05/2019
 * Time: 20:26
 */

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AbstractVoter
 * @package App\Security\Voter
 */
abstract class AbstractVoter extends Voter {
	/**
	 * @var AccessDecisionManagerInterface
	 */
	protected $accessDecisionManager;

	/**
	 * AbstractVoter constructor.
	 *
	 * @param AccessDecisionManagerInterface $accessDecisionManager
	 */
	public function __construct(AccessDecisionManagerInterface $accessDecisionManager)
	{
		$this->accessDecisionManager = $accessDecisionManager;
	}

	/**
	 * @param TokenInterface $token
	 *
	 * @return bool
	 */
	protected function isConnected(TokenInterface $token): bool
	{
		return $token->getUser() instanceof UserInterface;
	}
}