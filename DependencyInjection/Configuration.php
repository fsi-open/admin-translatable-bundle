<?php

namespace FSi\Bundle\AdminTranslatableBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('fsi_admin_translatable');
        $rootNode
            ->children()
                ->arrayNode('locales')
                    ->isRequired()
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('list')->defaultValue('@FSiAdminTranslatable/List/list.html.twig')->end()
                        ->scalarNode('form')->defaultValue('@FSiAdmin/Form/form.html.twig')->end()
                        ->scalarNode('resource')->defaultValue('@FSiAdmin/Resource/resource.html.twig')->end()
                        ->scalarNode('display')->defaultValue('@FSiAdmin/Display/display.html.twig')->end()

                        ->scalarNode('datagrid_theme')->defaultValue('@FSiAdminTranslatable/CRUD/datagrid.html.twig')->end()
                        ->scalarNode('datasource_theme')->defaultValue('@FSiAdmin/CRUD/datasource.html.twig')->end()

                        ->scalarNode('crud_list')->defaultValue('@FSiAdminTranslatable/CRUD/list.html.twig')->end()
                        ->scalarNode('crud_create')->defaultValue('@FSiAdmin/CRUD/create.html.twig')->end()
                        ->scalarNode('crud_edit')->defaultValue('@FSiAdmin/CRUD/edit.html.twig')->end()
                        ->scalarNode('crud_delete')->defaultValue('@FSiAdmin/CRUD/delete.html.twig')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
