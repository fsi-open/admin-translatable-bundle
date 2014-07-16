<?php

namespace FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class MapBuilderPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('fsi_resource_repository.map_builder')) {
            $definition = $container->getDefinition('fsi_resource_repository.map_builder');
            $definition->setClass('FSi\Bundle\AdminTranslatableBundle\Repository\TranslatableMapBuilder');
            $definition->addArgument($container->getDefinition('admin_translatable.manager.locale'));
        }
    }
}
