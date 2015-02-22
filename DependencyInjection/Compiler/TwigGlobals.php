<?php

namespace FSi\Bundle\AdminTranslatableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigGlobals implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('twig')) {
            return;
        }

        $parameters = array(
            'admin_translatable_templates_datagrid_theme' => $container->getParameter('admin_translatable.templates.datagrid_theme'),
            'admin_translatable_templates_datasource_theme' => $container->getParameter('admin_translatable.templates.datasource_theme'),
        );

        $twig = $container->findDefinition('twig');

        foreach ($parameters as $name => $parameter) {
            $twig->addMethodCall('addGlobal', array($name, $parameter));
        }
    }
}
