<?php
namespace Hostnet\Bundle\EntityTrackerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
class Configuration implements ConfigurationInterface
{
    const REVISION_FACTORY_INTERFACE  = 'Hostnet\Component\EntityRevision\Factory\RevisionFactoryInterface';
    const BLAMABLE_PROVIDER_INTERFACE = 'Hostnet\Component\EntityBlamable\Provider\BlamableProviderInterface';

    /**
     * @see \Symfony\Component\Config\Definition\ConfigurationInterface::getConfigTreeBuilder()
     */
    public function getConfigTreeBuilder()
    {
        $tree_builder = new TreeBuilder();
        $root_node    = $tree_builder->root('hostnet_entity_tracker');

        $component_info = 'Configures and enables the %s listener';

        $root_node
            ->children()
                ->arrayNode('blamable')
                    ->info(sprintf($component_info, 'blamable'))
                    ->children()
                        ->scalarNode('provider')
                            ->info('Provider implementation of ' . self::BLAMABLE_PROVIDER_INTERFACE)
                            ->cannotBeEmpty()
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('revision')
                    ->info(sprintf($component_info, 'revision'))
                    ->children()
                        ->scalarNode('factory')
                            ->info('Factory implementation of ' . self::REVISION_FACTORY_INTERFACE)
                            ->cannotBeEmpty()
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('mutation')
                    ->info(sprintf($component_info, 'mutation'))
                ->end()
            ->end();

        return $tree_builder;
    }
}
