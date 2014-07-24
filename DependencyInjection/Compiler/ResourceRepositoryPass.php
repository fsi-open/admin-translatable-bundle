<?php

namespace FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ResourceRepositoryPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('fsi_resource_repository.repository')) {
            $definition = $container->getDefinition('fsi_resource_repository.repository');
            $definition->setClass('FSi\Bundle\AdminTranslatableBundle\Repository\Repository');
        }
    }
}
