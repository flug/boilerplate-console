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

namespace Clooder\DependenciesInjection;

use Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationPass as BaseMergeExtensionConfigurationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MergeExtensionConfigurationPass extends BaseMergeExtensionConfigurationPass
{
    private $extensions;

    public function __construct(array $extensions)
    {
        $this->extensions = $extensions;
    }

    public function process(ContainerBuilder $container): void
    {
        foreach ($this->extensions as $extension) {
            if (!\count($container->getExtensionConfig($extension))) {
                $container->loadFromExtension($extension, []);
            }
        }
        parent::process($container);
    }
}
