<?php

namespace Webdevvie\PushoverBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;
use Webdevvie\PushoverBundle\Message\PushoverMessage;
use Webdevvie\PushoverBundle\Service\PushoverService;
use Webdevvie\PushoverBundle\Response\PushoverResponse;

/**
 * Class SendMessageCommand
 * @package Webdevvie\PushoverBundle\Command
 */
class SendMessageCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('pushover:send')
            ->setDescription('Sends a message through pushover');

        $this->addArgument('user', InputArgument::REQUIRED, 'user to notify');

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
        $user = $input->getArgument('user');
        $dialog = $this->getHelper('dialog');
        $pushover = $this->getContainer()->get('pushover');

        /**
         * @var PushoverService $pushover
         */

        /**
         * @var DialogHelper $dialog
         */
        $message = new PushoverMessage();
        $message->setUser($user);

        $selected = $dialog->select(
            $output,
            '<question>Please enter the tone to use:</question>',
            PushoverMessage::$availableSounds,
            0,
            false,
            'Value "%s" is invalid',
            false
        );

        $selectedSound =  PushoverMessage::$availableSounds[$selected];

        $message->setSound($selectedSound);

        $title = $dialog->askAndValidate(
            $output,
            '<question>Please enter your message title:</question>',
            function ($answer) {
                if (strlen($answer) < 1) {
                    throw new \RuntimeException(
                        'The title should be longer than 0 characters'
                    );
                }
                return $answer;
            }
        );
        $message->setTitle($title);
        $messageText = $dialog->askAndValidate(
            $output,
            '<question>Please enter your message:</question>',
            function ($answer) {
                if (strlen($answer) < 1) {
                    throw new \RuntimeException(
                        'The message should be longer than 0 characters'
                    );
                }
                return $answer;
            }
        );

        $message->setMessage($messageText);

        if (!$dialog->askConfirmation(
            $output,
            '<question>Send this message?</question>',
            false
        )
        ) {
            return;
        }
        $response = $pushover->sendMessage($message);
        if(count($response->getErrors())==0)
        {
            $output->writeln("<info>Message sent:</info>".$response->getRequestId());
        }
        else
        {

            $output->writeln("<error>Message not sent</error>");
            foreach($response->getErrors() as $error)
            {
                $output->writeln("<error>".$error."</error>");
            }
        }


    }
}