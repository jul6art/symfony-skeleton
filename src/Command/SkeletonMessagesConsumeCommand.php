<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class SkeletonMessagesConsumeCommand.
 */
class SkeletonMessagesConsumeCommand extends Command
{
    const QUEUE_PRIORITY_HIGH = 'async_priority_high';
    const QUEUE_PRIORITY_LOW = 'async_priority_low';
    const QUEUE_PARAMETER_HIGH = 'high';
    const QUEUE_PARAMETER_LOW = 'low';

    /**
     * @var string
     */
    protected static $defaultName = 'skeleton:messages:consume';

    protected function configure()
    {
        $this
            ->setDescription('Consume messages queues')
            ->addOption('transport', 't', InputOption::VALUE_OPTIONAL, 'Queue to consume')
            ->addOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'Time limit in seconds (default 90)', 90)
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->note('Processing messages consuming');

        $transports = [];

        if (\in_array($input->getOption('transport'), [null, self::QUEUE_PARAMETER_HIGH])) {
            $io->note('Consume high priority  messages');
            $transports[] = self::QUEUE_PRIORITY_HIGH;
        }

        if (\in_array($input->getOption('transport'), [null, self::QUEUE_PARAMETER_LOW])) {
            $io->note('Consume low priority  messages');
            $transports[] = self::QUEUE_PRIORITY_LOW;
        }

        if (\count($transports)) {
            $command = $this->getApplication()->find('messenger:consume');

            $arguments = [
                'command' => 'messenger:consume',
                'receivers' => $transports,
                '--time-limit' => (int) $input->getOption('limit'),
            ];

            $greetInput = new ArrayInput($arguments);
            $returnCode = $command->run($greetInput, $output);
        }

        $io->success('Messages successfully consumed!');

        return $returnCode;
    }
}
