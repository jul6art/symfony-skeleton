<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 21/05/2019
 * Time: 20:26.
 */

namespace App\Security\Voter;

use DH\DoctrineAuditBundle\Reader\AuditReader;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AbstractVoter.
 */
abstract class AbstractVoter extends Voter
{
    /**
     * @var AccessDecisionManagerInterface
     */
    protected $accessDecisionManager;

    /**
     * @var AuditReader
     */
    protected $auditReader;

    /**
     * @var int
     */
    protected $audit_limit;

    /**
     * AbstractVoter constructor.
     *
     * @param AccessDecisionManagerInterface $accessDecisionManager
     */
    public function __construct(AccessDecisionManagerInterface $accessDecisionManager, AuditReader $auditReader, int $audit_limit)
    {
        $this->accessDecisionManager = $accessDecisionManager;
        $this->auditReader = $auditReader;
        $this->audit_limit = $audit_limit;
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
