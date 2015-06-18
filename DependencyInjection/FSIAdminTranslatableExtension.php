<?php

namespace FSi\Bundle\AdminTranslatableBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class FSIAdminTranslatableExtension extends Extension implements PrependExtensionInterface
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
        $loader->load('form.xml');
        $loader->load('datagrid.xml');
        $loader->load('menu.xml');

        $loader->load('context/batch.xml');
        $loader->load('context/display.xml');
        $loader->load('context/form.xml');
        $loader->load('context/list.xml');
    }

    /**
     * @inheritdoc
     */
    public function prepend(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('fsi_admin', array(
            'templates' => array(
                'datagrid_theme' => '@FSiAdminTranslatable/DataGrid/translatable_datagrid.html.twig',
                'form_theme' => '@FSiAdminTranslatable/Form/translatable_form.html.twig'
            )
        ));
    }
}
