<?php
/**
 * @copyright 2014-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Bundle\EntityTrackerBundle\DependencyInjection;

use Hostnet\Component\EntityBlamable\Blamable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers \Hostnet\Bundle\EntityTrackerBundle\DependencyInjection\HostnetEntityTrackerExtension
 */
class HostnetEntityTrackerExtensionTest extends TestCase
{
    private $container;

    /**
     * @var HostnetEntityTrackerExtension
     */
    private $extension;

    public function setUp(): void
    {
        $this->container = new ContainerBuilder();

        $this->extension = $this->getMockBuilder(HostnetEntityTrackerExtension::class)
            ->setMethods(['validateComponent', 'validateClass'])
            ->getMock();
    }

    public function testLoadRevision(): void
    {
        $this->extension->load(['entity_tracker' => ['revision' => ['factory' => 'henk']]], $this->container);

        $definition = $this->container->getDefinition('entity_tracker.listener.revision');
        self::assertEquals('henk', $definition->getArgument(1));
    }

    public function testLoadBlamable(): void
    {
        $configs = ['entity_tracker' => ['blamable' => ['provider' => 'henk', 'default_username' => 'eux']]];

        $this->container->setParameter('kernel.bundles', ['SecurityBundle' => 'BlahBlahClass']);
        $this->extension->expects(self::once())->method('validateComponent')->with(Blamable::class, 'blamable');
        $this->extension->load($configs, $this->container);

        $definition = $this->container->getDefinition('entity_tracker.listener.blamable');
        self::assertEquals('henk', $definition->getArgument(1));

        $default_username_definition = $this->container->getDefinition(Configuration::BLAMABLE_DEFAULT_PROVIDER);
        self::assertEquals('eux', $default_username_definition->getArgument(1));
    }

    public function testLoadMutation(): void
    {
        $configs = ['entity_tracker' => ['mutation' => []]];

        $this->extension->expects(self::once())
            ->method('validateComponent')
            ->with('Hostnet\Component\EntityMutation\Mutation', 'mutation');

        $this->extension->load($configs, $this->container);
    }

    public function testValidateComponentClassFails(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->validateComponent('Henk', 'henk');
    }

    public function testValidateComponentClassSuccess(): void
    {
        $this->expectNotToPerformAssertions();
        $this->validateComponent(__CLASS__, 'henk');
    }

    public function testValidateClassFails(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->validateClass(__CLASS__, 'henk');
    }

    public function testValidateClassSuccess(): void
    {
        $this->expectNotToPerformAssertions();
        $this->validateClass('Henk', 'henk');
    }

    private function validateComponent(string $annotation, string $config): void
    {
        $method = (new \ReflectionClass(HostnetEntityTrackerExtension::class))->getMethod('validateComponent');
        $method->setAccessible(true);

        $method->invoke(new HostnetEntityTrackerExtension(), $annotation, $config);
    }

    private function validateClass(string $annotation, string $config): void
    {
        $method = (new \ReflectionClass(HostnetEntityTrackerExtension::class))->getMethod('validateClass');
        $method->setAccessible(true);

        $method->invoke(new HostnetEntityTrackerExtension(), $annotation, $config);
    }
}
