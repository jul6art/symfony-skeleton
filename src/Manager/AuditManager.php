<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/04/2019
 * Time: 13:09.
 */

namespace App\Manager;

use DH\DoctrineAuditBundle\AuditConfiguration;
use DH\DoctrineAuditBundle\Helper\AuditHelper;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use DH\DoctrineAuditBundle\AuditManager as BaseAuditManager;
use Doctrine\ORM\Mapping\MappingException;

/**
 * Class AuditManager.
 */
class AuditManager extends AbstractManager
{
    /**
     * @var BaseAuditManager
     */
    private $auditManager;

    /**
     * @var AuditHelper
     */
    private $helper;

    /**
     * @var AuditConfiguration
     */
    private $configuration;

    /**
     * AuditManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param BaseAuditManager       $auditManager
     * @param AuditHelper            $helper
     */
    public function __construct(EntityManagerInterface $entityManager, BaseAuditManager $auditManager, AuditHelper $helper, AuditConfiguration $configuration)
    {
        parent::__construct($entityManager);
        $this->auditManager = $auditManager;
        $this->helper = $helper;
        $this->configuration = $configuration;
    }

    /**
     * @param string $level
     * @param $entity
     * @param array $diffs
     *
     * @throws DBALException
     * @throws MappingException
     */
    public function audit(string $level, $entity, array $diffs): void
    {
        $meta = $this->entityManager->getClassMetadata(get_class($entity));

        $blame = $this->helper->blame();

        $schema = $meta->getSchemaName() ? sprintf('%s.', $meta->getSchemaName()) : '';
        $auditTable = $schema.$this->configuration->getTablePrefix().$meta->getTableName().$this->configuration->getTableSuffix();
        $fields = [
            'type' => ':type',
            'object_id' => ':object_id',
            'diffs' => ':diffs',
            'blame_id' => ':blame_id',
            'blame_user' => ':blame_user',
            'blame_user_fqdn' => ':blame_user_fqdn',
            'blame_user_firewall' => ':blame_user_firewall',
            'ip' => ':ip',
            'created_at' => ':created_at',
        ];

        $query = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $auditTable,
            implode(', ', array_keys($fields)),
            implode(', ', array_values($fields))
        );

        $statement = $this->entityManager->getConnection()->prepare($query);

        $statement->bindValue('type', $level);
        $statement->bindValue('object_id', (string) $this->helper->id($this->entityManager, $entity));
        $statement->bindValue('diffs', json_encode($diffs));
        $statement->bindValue('blame_id', $blame['user_id']);
        $statement->bindValue('blame_user', $blame['username']);
        $statement->bindValue('blame_user_fqdn', $blame['user_fqdn']);
        $statement->bindValue('blame_user_firewall', $blame['user_firewall']);
        $statement->bindValue('ip', $blame['client_ip']);
        $statement->bindValue('created_at', (new \DateTime())->format('Y-m-d H:i:s'));
        $statement->execute();
    }
}
