<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Command;

use App\Manager\Traits\SessionManagerAwareTrait;
use Doctrine\DBAL\DBALException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SkeletonSessionsPurgeCommand.
 */
class SkeletonSessionsPurgeCommand extends Command implements ContainerAwareInterface
{
    use SessionManagerAwareTrait;

    /**
     * @var string
     */
    protected static $defaultName = 'skeleton:sessions:purge';

    /**
     * @var ContainerInterface|null
     */
    protected $container;

    protected function configure()
    {
        $this
            ->setDescription('Remove outdated sessions from database')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->note('Removing outdated sessions from database');

        $this->sessionManager->purge($this->container->getParameter('session_lifetime'));

        $io->success('All outdated sessions have been removed');

        return 0;
    }

    /**
     * Sets the container.
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
