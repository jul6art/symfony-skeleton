<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
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
     * AbstractVoter constructor.
     *
     * @param AccessDecisionManagerInterface $accessDecisionManager
     * @param AuditReader                    $auditReader
     */
    public function __construct(AccessDecisionManagerInterface $accessDecisionManager, AuditReader $auditReader)
    {
        $this->accessDecisionManager = $accessDecisionManager;
        $this->auditReader = $auditReader;
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
