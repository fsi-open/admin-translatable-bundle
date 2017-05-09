<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

class ResourceRepositoryPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasExtension('fsi_resource_repository')) {
            $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));
            $loader->load('context/resource.xml');

            $contextManagerDefinition = $container->getDefinition('admin.context.manager');
            $contextManagerDefinition->addMethodCall('addContext', [
                new Reference('admin_translatable.resource.context')
            ]);

            $definition = $container->getDefinition('fsi_resource_repository.resource.repository');
            $translatableMapBuilderDefinition = $container->getDefinition('admin_translatable.resource.map_builder');
            $translatableMapBuilderDefinition->setArguments([
                '%fsi_resource_repository.resource.map_path%',
                '%fsi_resource_repository.resource.types%',
                new Reference('fsi_doctrine_extensions.listener.translatable')
            ]);

            $arguments = $definition->getArguments();
            $arguments[0] = $translatableMapBuilderDefinition;
            $definition->setArguments($arguments);
        }
    }
}
