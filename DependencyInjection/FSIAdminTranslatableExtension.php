<?php

namespace FSi\Bundle\AdminTranslatableBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class FSIAdminTranslatableExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('fsi_admin_translatable.locales', $config['locales']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('controller.xml');
        $loader->load('listeners.xml');
        $loader->load('datagrid.xml');

        $loader->load('context/batch.xml');
        $loader->load('context/display.xml');
        $loader->load('context/form.xml');
        $loader->load('context/list.xml');
        $loader->load('context/resource.xml');
    }
}
