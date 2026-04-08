<?php
/**
 * @copyright 2014-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Bundle\EntityTrackerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const string REVISION_FACTORY_INTERFACE
        = 'Hostnet\Component\EntityRevision\Factory\RevisionFactoryInterface';

    public const string BLAMABLE_PROVIDER_INTERFACE
        = 'Hostnet\Component\EntityBlamable\Provider\BlamableProviderInterface';

    public const string BLAMABLE_DEFAULT_PROVIDER
        = 'entity_tracker.provider.blamable';

    private const string CONFIG_ROOT = 'hostnet_entity_tracker';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $tree_builder = new TreeBuilder(self::CONFIG_ROOT);
        $root_node    = $tree_builder->getRootNode();

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
                            ->defaultValue(self::BLAMABLE_DEFAULT_PROVIDER)
                        ->end()
                        ->scalarNode('default_username')
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
