<?php

namespace Clooder\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloCommand extends ContainerAwareConsole
{
    protected function configure()
    {
        $this->setName('hello')
            ->addArgument('name')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('Hello %s ', $input->getArgument('name')));
    }
}
