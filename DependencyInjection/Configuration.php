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
                        ->scalarNode('crud_list')->defaultValue('@FSiAdminTranslatable/CRUD/list.html.twig')->end()
                        ->scalarNode('crud_create')->defaultValue('@FSiAdminTranslatable/CRUD/create.html.twig')->end()
                        ->scalarNode('crud_edit')->defaultValue('@FSiAdminTranslatable/CRUD/edit.html.twig')->end()
                        ->scalarNode('crud_delete')->defaultValue('@FSiAdminTranslatable/CRUD/delete.html.twig')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
