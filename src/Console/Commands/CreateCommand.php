<?php

namespace Rmtram\Pages\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateCommand
 * @package Rmtram\Pages\Console\Commands
 */
class CreateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('create')
            ->setDescription('create static pages.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}