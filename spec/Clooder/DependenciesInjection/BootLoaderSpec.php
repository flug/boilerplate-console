<?php

namespace spec\Clooder\DependenciesInjection;

use Clooder\DependenciesInjection\BootLoader;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BootLoaderSpec extends ObjectBehavior
{

        function let(\Clooder\Kernel $kernel)
        {
                $kernel->getKernelRootDir()->willReturn(getcwd().'/src');
                $this->beConstructedWith($kernel);

        }
        function it_is_initializable()
        {
                $this->shouldHaveType(BootLoader::class);
        }

        function it_initialisation_of_container($kernel)
        {
                $this->getContainer()->shouldReturnAnInstanceOf(\Psr\Container\ContainerInterface::class);
        }


        function it_load_configuration_depenencies()
        {
                $this->load();
        }
        
}
