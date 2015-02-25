<?php

namespace FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class MapBuilderPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('fsi_resource_repository.resource.map_builder')) {
            $definition = $container->getDefinition('fsi_resource_repository.resource.map_builder');
            $definition->setClass('FSi\Bundle\AdminTranslatableBundle\Repository\TranslatableMapBuilder');
            $definition->addArgument(new Reference('fsi_doctrine_extensions.listener.translatable'));
        }
    }
}
