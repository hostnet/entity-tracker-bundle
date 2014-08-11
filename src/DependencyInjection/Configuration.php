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
        $root_node    = $tree_builder->root('entity_tracker');

        $root_node
            ->children()
                ->arrayNode('blamable')
                    ->children()
                        ->scalarNode('provider')
                            ->info('The service for a custom implementation of ' . self::BLAMABLE_PROVIDER_INTERFACE)
                            ->cannotBeEmpty()
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('revision')
                    ->children()
                        ->scalarNode('factory')
                            ->info('The service for a custom implementation of ' . self::REVISION_FACTORY_INTERFACE)
                            ->cannotBeEmpty()
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('mutation')
                ->end()
            ->end();

        return $tree_builder;
    }
}
