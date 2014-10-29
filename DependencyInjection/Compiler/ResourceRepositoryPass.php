<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        if ($container->hasDefinition('fsi_resource_repository.resource.repository')) {
            $definition = $container->getDefinition('fsi_resource_repository.resource.repository');
            $arguments = $definition->getArguments();
            $arguments[0] = $container->getDefinition('admin_translatable.resource.map_builder');
            $definition->setArguments($arguments);
        }
    }
}
