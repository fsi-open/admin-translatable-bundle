<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class FSIAdminTranslatableExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration([
            'crud_list' => $container->getParameter('admin.templates.crud_list'),
            'crud_form' => $container->getParameter('admin.templates.crud_form'),
            'list' => $container->getParameter('admin.templates.list'),
            'form' => $container->getParameter('admin.templates.form'),
            'resource' => $container->getParameter('admin.templates.resource'),
            'display' => $container->getParameter('admin.templates.display'),
        ]);
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('fsi_admin_translatable.locales', $config['locales']);

        $this->setTemplateParameters($container, $config['templates']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig('fsi_admin', [
            'templates' => [
                'datagrid_theme' => '@FSiAdminTranslatable/DataGrid/translatable_datagrid.html.twig',
                'form_theme' => '@FSiAdminTranslatable/Form/translatable_form.html.twig'
            ]
        ]);
    }

    protected function setTemplateParameters(ContainerBuilder $container, array $config = []): void
    {
        foreach ($config as $key => $value) {
            $container->setParameter(sprintf('admin_translatable.templates.%s', $key), $value);
        }
    }
}
