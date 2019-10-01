<?php

namespace spec\Clooder;

use Clooder\Kernel;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Application;

class KernelSpec extends ObjectBehavior
{
        function let(Application $application)
        {
                $this->beConstructedWith($application);
        }

        function it_is_initializable()
        {
                $this->shouldHaveType(Kernel::class);
        }

        function it_the_path_of_kernel_root()
        {

                $this->getKernelRootDir()->shouldReturn('/app/src');
        }

        function it_get_application_context()
        {
                $this->getApplication()->shouldReturnAnInstanceOf(Application::class);
        }

}
