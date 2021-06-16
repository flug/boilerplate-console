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

namespace spec\Clooder\Console;

use Clooder\Console\Application;
use Clooder\Kernel;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ApplicationSpec extends ObjectBehavior
{
    public function let(Kernel $kernel, ContainerInterface $container, EventDispatcherInterface $eventDispatcher): void
    {
        $container->set('event_dispatcher', $eventDispatcher->getWrappedObject());
        $kernel->getContainer()->willReturn($container);
        $this->beConstructedWith($kernel);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Application::class);
        $this->beAnInstanceOf(\Symfony\Component\Console\Application::class);
    }

    public function it_is_about_testing_the_addition_of_a_command(Kernel $kernel): void
    {
        $kernel->boot()->shouldBeCalledOnce();
        $command = new Command('example');
        $this->add($command)->shouldReturn($command);
        $this->get('example')->shouldReturn($command);
    }

    public function it_is_about_having_all_the_commands_available(Kernel $kernel): void
    {
        $kernel->boot()->shouldBeCalledOnce();
        $command1 = new Command('example1');
        $command2 = new Command('example2');
        $command3 = new Command('example3');
        $this->add($command1);
        $this->add($command2);
        $this->add($command3);
        $this->all()->shouldReturn([
            'help' => $this->get('help'),
            'list' => $this->get('list'),
            'example1' => $command1,
            'example2' => $command2,
            'example3' => $command3,
        ]);
    }

    public function it_is_a_question_of_whether_a_command_exists(Kernel $kernel): void
    {
        $kernel->boot()->shouldBeCalledOnce();
        $command = new Command('example');
        $this->add($command);
        $this->has('example')->shouldReturn(true);
    }

    public function it_is_a_question_of_whether_a_command_does_not_exist(Kernel $kernel): void
    {
        $kernel->boot()->shouldBeCalledOnce();
        $command = new Command('example');
        $this->has('example')->shouldReturn(false);
    }
}
