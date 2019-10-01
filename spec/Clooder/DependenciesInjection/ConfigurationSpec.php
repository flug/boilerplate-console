<?php

namespace spec\Clooder\DependenciesInjection;

use Clooder\DependenciesInjection\Configuration;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigurationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Configuration::class);
    }

    function it_instanceof_config_tree_builder()
    {
            $this->getConfigTreeBuilder()->shouldReturnAnInstanceOf(\Symfony\Component\Config\Definition\Builder\TreeBuilder::class);
    }
}
