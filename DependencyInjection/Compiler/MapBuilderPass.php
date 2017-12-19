<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler;

use FSi\Bundle\AdminTranslatableBundle\Repository\TranslatableMapBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class MapBuilderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('fsi_resource_repository.resource.map_builder')) {
            $definition = $container->getDefinition('fsi_resource_repository.resource.map_builder');
            $definition->setClass(TranslatableMapBuilder::class);
            $definition->addArgument(new Reference('fsi_doctrine_extensions.listener.translatable'));
        }
    }
}
