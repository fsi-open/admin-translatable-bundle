<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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

    public function getConfigTreeBuilder(): TreeBuilder
    {
        if (true === method_exists(TreeBuilder::class, 'getRootNode')) {
            $treeBuilder = new TreeBuilder('fsi_admin_translatable');
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('fsi_admin_translatable');
        }

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
