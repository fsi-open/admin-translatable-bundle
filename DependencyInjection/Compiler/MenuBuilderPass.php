<?php

namespace FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class MenuBuilderPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('admin.menu.builder')) {
            $definition = $container->getDefinition('admin.menu.builder');
            $definition->setClass('FSi\Bundle\AdminTranslatableBundle\Menu\MenuBuilder');
            $definition->addArgument($container->getDefinition('admin_translatable.manager.locale'));
        }
    }
}
