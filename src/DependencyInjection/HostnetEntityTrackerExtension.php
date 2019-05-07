<?php
/**
 * @copyright 2014-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Bundle\EntityTrackerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class HostnetEntityTrackerExtension extends Extension
{
    private const BLAMABLE = 'Hostnet\Component\EntityBlamable\Blamable';
    private const MUTATION = 'Hostnet\Component\EntityMutation\Mutation';
    private const REVISION = 'Hostnet\Component\EntityRevision\Revision';

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader        = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader->load('services.yaml');

        if (\array_key_exists('blamable', $config)) {
            $this->validateComponent(self::BLAMABLE, 'blamable');
            $loader->load('blamable.yaml');
            if ($container->hasParameter('kernel.bundles')
                && \array_key_exists('SecurityBundle', $container->getParameter('kernel.bundles'))
            ) {
                $loader->load('security.yaml');
            }

            $container
                ->getDefinition('entity_tracker.listener.blamable')
                ->replaceArgument(1, new Reference($config['blamable']['provider']));

            if (isset($config['blamable']['default_username'])) {
                $container
                    ->getDefinition(Configuration::BLAMABLE_DEFAULT_PROVIDER)
                    ->replaceArgument(1, $config['blamable']['default_username']);
            }
        } else {
            $this->validateClass(self::BLAMABLE, 'blamable');
        }

        if (\array_key_exists('revision', $config)) {
            $this->validateComponent(self::REVISION, 'revision');
            $loader->load('revision.yaml');
            $container
                ->getDefinition('entity_tracker.listener.revision')
                ->replaceArgument(1, new Reference($config['revision']['factory']));
        } else {
            $this->validateClass(self::REVISION, 'revision');
        }

        if (\array_key_exists('mutation', $config)) {
            $this->validateComponent(self::MUTATION, 'mutation');
            $loader->load('mutation.yaml');
        } else {
            $this->validateClass(self::MUTATION, 'mutation');
        }
    }

    /**
     * @param string $annotation_class
     * @param string $config_name
     *
     * @throws \RuntimeException
     */
    protected function validateComponent(string $annotation_class, string $config_name): void
    {
        if (class_exists($annotation_class)) {
            return;
        }

        throw new \RuntimeException(sprintf(
            'You have configured entity_tracker.%1$s, but you did not require the package. ' .
            'To use this option, please require "hostnet/entity-%1$s-component".',
            $config_name
        ));
    }

    /**
     * @param string $annotation_class
     * @param string $config_name
     *
     * @throws \RuntimeException
     */
    protected function validateClass(string $annotation_class, string $config_name): void
    {
        if (!class_exists($annotation_class)) {
            return;
        }

        throw new \RuntimeException(sprintf(
            'You have required "hostnet/entity-%1$s-component" but you did not configure entity_tracker.%1$s',
            $config_name
        ));
    }
}
