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
        $container->setParameter('fsi_admin_translatable.languages', $config['languages']);
        $container->setParameter('admin.templates.base', '@FSiAdminTranslatable/base.html.twig');
        $this->setTemplateParameters($container, $config['templates']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('listeners.xml');
        $loader->load('context/create.xml');
        $loader->load('context/edit.xml');
        $loader->load('context/delete.xml');
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array $config
     */
    protected function setTemplateParameters(ContainerBuilder $container, $config = array())
    {
        foreach ($config as $key => $value) {
            $container->setParameter(sprintf('admin_translatable.templates.%s', $key), $value);
        }
    }
}
