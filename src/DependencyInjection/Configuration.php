<?php
/**
 * @copyright 2014-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Bundle\EntityTrackerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\HttpKernel\Kernel;

class Configuration implements ConfigurationInterface
{
    public const REVISION_FACTORY_INTERFACE  = 'Hostnet\Component\EntityRevision\Factory\RevisionFactoryInterface';
    public const BLAMABLE_PROVIDER_INTERFACE = 'Hostnet\Component\EntityBlamable\Provider\BlamableProviderInterface';
    public const BLAMABLE_DEFAULT_PROVIDER   = 'entity_tracker.provider.blamable';

    private const CONFIG_ROOT = 'hostnet_entity_tracker';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $tree_builder = $this->createTreeBuilder();
        $root_node    = $this->retrieveRootNode($tree_builder);

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

    private function createTreeBuilder(): TreeBuilder
    {
        if (Kernel::VERSION_ID >= 40200) {
            return new TreeBuilder(self::CONFIG_ROOT);
        }

        if (Kernel::VERSION_ID < 40200) {
            return new TreeBuilder();
        }

        throw new \RuntimeException('This bundle can only be used by Symfony 4.0 and up.');
    }

    private function retrieveRootNode(TreeBuilder $tree_builder): NodeDefinition
    {
        if (Kernel::VERSION_ID >= 40200) {
            return $tree_builder->getRootNode();
        }

        if (Kernel::VERSION_ID < 40200) {
            return $tree_builder->root(self::CONFIG_ROOT);
        }

        throw new \RuntimeException('This bundle can only be used by Symfony 4.0 and up.');
    }
}
