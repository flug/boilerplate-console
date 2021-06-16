<?php

/*
 * This file is part of Boilerplate console project.
 *
 * (c) Flug <flugv1@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Clooder\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('hello')
            ->addArgument('name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('Hello %s ', $input->getArgument('name')));

        return 0;
    }
}
