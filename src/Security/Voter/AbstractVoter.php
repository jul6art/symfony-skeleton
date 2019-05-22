<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 21/05/2019
 * Time: 20:26
 */

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

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
}