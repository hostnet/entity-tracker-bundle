<?php
namespace Hostnet\Bundle\EntityTrackerBundle\DependencyInjection\DependencyInjection;

use Hostnet\Bundle\EntityTrackerBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;
use Symfony\Component\Config\Definition\Processor;

/**
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @covers Hostnet\Bundle\EntityTrackerBundle\DependencyInjection\Configuration
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider invalidProvider
     * @param array $configs
     */
    public function testInvalid(array $configs)
    {
        $processor     = new Processor();
        $configuration = new Configuration();

        try {
            $processor->processConfiguration($configuration, $configs);
        } catch (InvalidTypeException $e) {
            return;
        } catch (InvalidConfigurationException $e) {
            return;
        }
    }

    /**
     * @return array
     */
    public function invalidProvider()
    {
        return [
            [['test']],
            ['entity_tracker' => ['henk']],
            ['entity_tracker' => ['blamable' => []]],
            ['entity_tracker' => ['revision' => []]],
            ['entity_tracker' => ['mutation' => ['henk' => 'hans']]],
            ['entity_tracker' => ['blamable' => ['henk' => 'hans']]],
            ['entity_tracker' => ['revision' => ['henk' => 'hans']]],
        ];
    }


    /**
     * Full valid case
     */
    public function testValid()
    {
        $configs = [
            'entity_tracker' => [
                'blamable' => ['provider' => 'henk', 'default_username' => 'eux'],
                'revision' => ['factory'  => 'henk'],
                'mutation' => [],
            ]
        ];

        $processor     = new Processor();
        $configuration = new Configuration();

        $processed = $processor->processConfiguration($configuration, $configs);
        $this->assertEquals($configs['entity_tracker'], $processed);
    }

    /**
     * Empty config is allowed, means nothing is configured
     *
     * @dataProvider emptyConfigProvider
     * @param array $configs
     */
    public function testEmptyConfig(array $configs)
    {
        $processor     = new Processor();
        $configuration = new Configuration();

        $this->assertEmpty($processor->processConfiguration($configuration, $configs));
    }

    /**
     * @return array
     */
    public function emptyConfigProvider()
    {
        return [
            [[]],
            [['entity_tracker' => null]],
            [['entity_tracker' => []]]
        ];
    }
}
