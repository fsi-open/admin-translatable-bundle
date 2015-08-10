<?php

namespace FSi\Bundle\AdminTranslatableBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var array
     */
    private $adminTemplates;

    public function __construct(array $adminTemplates)
    {
        $this->adminTemplates = $adminTemplates;
    }

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
                        ->scalarNode('list')->defaultValue($this->adminTemplates['list'])->end()
                        ->scalarNode('form')->defaultValue($this->adminTemplates['form'])->end()
                        ->scalarNode('crud_list')->defaultValue($this->adminTemplates['crud_list'])->end()
                        ->scalarNode('crud_form')->defaultValue($this->adminTemplates['crud_form'])->end()
                        ->scalarNode('resource')->defaultValue($this->adminTemplates['resource'])->end()
                        ->scalarNode('display')->defaultValue($this->adminTemplates['display'])->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
