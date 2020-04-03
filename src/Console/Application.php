<?php

declare(strict_types=1);

namespace Clooder\Console;

use Clooder\Kernel;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class Application extends BaseApplication
{
    private $commandsRegistered = false;
    private $kernel;
    private $registrationErrors;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
        parent::__construct(Kernel::NAME, Kernel::VERSION);
    }

    public function add(Command $command)
    {
        $this->registerCommands();

        return parent::add($command);
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->registerCommands();
        if ($this->registrationErrors) {
            $this->renderRegistrationErrors($input, $output);
        }
        $this->setDispatcher($this->kernel->getContainer()->get('event_dispatcher'));

        return parent::doRun($input, $output);
    }

    public function get(string $name)
    {
        $this->registerCommands();
        $command = parent::get($name);
        if ($command instanceof ContainerAwareInterface) {
            $command->setContainer($this->kernel->getContainer());
        }

        return $command;
    }

    public function has(string $name)
    {
        $this->registerCommands();

        return parent::has($name);
    }

    public function all(string $namespace = null)
    {
        $this->registerCommands();

        return parent::all($namespace);
    }

    protected function registerCommands(): void
    {
        if ($this->commandsRegistered) {
            return;
        }
        $this->commandsRegistered = true;
        $this->kernel->boot();
        $container = $this->kernel->getContainer();
        if ($container->has('console.command_loader')) {
            $this->setCommandLoader($container->get('console.command_loader'));
        }

        if ($container->hasParameter('console.command.ids')) {
            $lazyCommandIds = $container->hasParameter('console.lazy_command.ids') ? $container->getParameter('console.lazy_command.ids') : [];
            foreach ($container->getParameter('console.command.ids') as $id) {
                if (!isset($lazyCommandIds[$id])) {
                    try {
                        $this->add($container->get($id));
                    } catch (\Exception $e) {
                        $this->registrationErrors[] = $e;
                    } catch (\Throwable $e) {
                        $this->registrationErrors[] = new FatalThrowableError($e);
                    }
                }
            }
        }
    }

    private function renderRegistrationErrors(InputInterface $input, OutputInterface $output): void
    {
        if ($output instanceof ConsoleOutputInterface) {
            $output = $output->getErrorOutput();
        }
        (new SymfonyStyle($input, $output))->warning('Some commands could not be registered:');
        foreach ($this->registrationErrors as $error) {
            $this->renderThrowable($error, $output);
        }
    }
}
