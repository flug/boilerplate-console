<?php

namespace Clooder;

class Kernel
{
    private $config;
    private $application;
    
    public function __construct(
        \Symfony\Component\Console\Application $application
    ) {
        $this->application = $application;
        $this->config = new \Clooder\DependenciesInjection\BootLoader($this);
        $this->loadConfiguration();
    }
    
    private function loadConfiguration()
    {
        $this->config->load();
    }
    
    public function getKernelRootDir(): string
    {
        return __DIR__;
    }
    
    public function boot()
    {
        $this->buildCommands();
        $this->application->run();
    }
    
    public function buildCommands()
    {
        $commands = [];
        foreach ($this->registerCommands() as $command) {
            if (!$command instanceof \Clooder\Command\ContainerAwareConsole) {
                continue;
            }
            $command->setContainer($this->config->getContainer());
            $commands[] = $command;
        }
        $this->application->addCommands($commands);
    }
    
    private function registerCommands(): iterable
    {
        yield new \Clooder\Command\HelloCommand();
    }
    
    public function getApplication()
    {
        return $this->application;
    }
}
