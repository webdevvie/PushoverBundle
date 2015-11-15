<?php

namespace Webdevvie\PushoverBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;
use Webdevvie\PushoverBundle\Service\PushoverService;

/**
 * Class CancelRetryCommand
 * @package Webdevvie\PushoverBundle\Command
 */
class CancelRetryCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('pushover:cancel:retry')
            ->setDescription('Cancel retry');

        $this->addArgument('receipt', InputArgument::REQUIRED, 'Receipt code');

    }

    /**
     * {@inheritDoc}
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pushover = $this->getContainer()->get('pushover');
        /**
         * @var PushoverService $pushover
         */

        $receipt = $input->getArgument('receipt');
        $response = $pushover->cancelReceipt($receipt);

        if (count($response->getErrors()) == 0) {
            $output->writeln("<info>Message cancelled:</info>" . $response->getRequestId());
            if ($response->getReceipt() != '') {
                $output->writeln("<info>Receipt:</info>" . $response->getReceipt());
            }
        } else {
            $output->writeln("<error>Message not cancelled</error>");
            foreach ($response->getErrors() as $error) {
                $output->writeln("<error>" . $error . "</error>");
            }
        }
    }
}
