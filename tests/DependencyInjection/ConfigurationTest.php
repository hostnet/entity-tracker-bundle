<?php
/**
 * @copyright 2014-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Bundle\EntityTrackerBundle\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * @covers \Hostnet\Bundle\EntityTrackerBundle\DependencyInjection\Configuration
 */
class ConfigurationTest extends TestCase
{
    public function testValid(): void
    {
        $configs = [
            'entity_tracker' => [
                'blamable' => ['provider' => 'henk', 'default_username' => 'eux'],
                'revision' => ['factory' => 'henk'],
                'mutation' => [],
            ],
        ];

        $processor     = new Processor();
        $configuration = new Configuration();

        $processed = $processor->processConfiguration($configuration, $configs);
        self::assertEquals($configs['entity_tracker'], $processed);
    }

    /**
     * @dataProvider emptyConfigProvider
     */
    public function testEmptyConfig(array $configs): void
    {
        $processor     = new Processor();
        $configuration = new Configuration();

        self::assertEmpty($processor->processConfiguration($configuration, $configs));
    }

    public function emptyConfigProvider(): array
    {
        return [
            [[]],
            [['entity_tracker' => null]],
            [['entity_tracker' => []]],
        ];
    }
}
