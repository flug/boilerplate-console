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

namespace Clooder;

use Clooder\Kernel\BootKernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Console\DependencyInjection\AddConsoleCommandPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;

class Kernel
{
    use BootKernel;
    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';
    private $config;
    private $application;
    private $container;

    public function getApplication()
    {
        return $this->application;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }

    public function boot(): void
    {
        $container = $this->buildContainer();
        $this->container = $container;
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->addCompilerPass(new AddConsoleCommandPass());
        $container->addCompilerPass(new RegisterListenersPass());
        $configDir = $this->getProjectDir().'/config';
        $loader->load($configDir.'/*'.self::CONFIG_EXTS, 'glob');
    }
}
