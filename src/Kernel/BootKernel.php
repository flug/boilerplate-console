<?php

declare(strict_types=1);

namespace Clooder\Kernel;

use Clooder\DependenciesInjection\MergeExtensionConfigurationPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\ClosureLoader;
use Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
use Symfony\Component\DependencyInjection\Loader\GlobFileLoader;
use Symfony\Component\DependencyInjection\Loader\IniFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

trait BootKernel
{
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(function (ContainerBuilder $container) use ($loader): void {
            $this->configureContainer($container, $loader);
            $container->addObjectResource($this);
        });
    }

    public function buildContainer()
    {
        $container = new ContainerBuilder();
        $container->set('kernel', $this);

        $container->addObjectResource($this);
        $this->registerContainerConfiguration($this->getContainerLoader($container));
        $this->prepareContainer($container);
        if (!$container->isCompiled()) {
            $container->compile();
        }

        return $container;
    }

    abstract protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void;

    protected function prepareContainer(ContainerBuilder $container): void
    {
        $extensions = [];
        foreach ($container->getExtensions() as $extension) {
            $extensions[] = $extension->getAlias();
        }

        // ensure these extensions are implicitly loaded
        $container->getCompilerPassConfig()->setMergePass(new MergeExtensionConfigurationPass($extensions));
    }

    protected function getContainerLoader(ContainerBuilder $container)
    {
        $locator = new FileLocator();
        $resolver = new LoaderResolver([
            new XmlFileLoader($container, $locator),
            new YamlFileLoader($container, $locator),
            new IniFileLoader($container, $locator),
            new PhpFileLoader($container, $locator),
            new GlobFileLoader($container, $locator),
            new DirectoryLoader($container, $locator),
            new ClosureLoader($container),
        ]);

        return new DelegatingLoader($resolver);
    }
}
