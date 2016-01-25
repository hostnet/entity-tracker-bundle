<?php
namespace Hostnet\Bundle\EntityTrackerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Test the DoctrineExtensionBundleExtension methods
 *
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @covers Hostnet\Bundle\EntityTrackerBundle\DependencyInjection\HostnetEntityTrackerExtension
 */
class HostnetEntityTrackerExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $ext;
    private $container;

    public function setUp()
    {
        $this->ext = $this
            ->getMockBuilder('Hostnet\Bundle\EntityTrackerBundle\DependencyInjection\HostnetEntityTrackerExtension')
            ->setMethods(['validateComponent', 'validateClass'])
            ->getMock();

        $this->container = new ContainerBuilder();
    }

    public function testLoadRevision()
    {
        $this->ext->load(['entity_tracker' => ['revision' => ['factory' => 'henk']]], $this->container);
        $definition = $this->container->getDefinition('entity_tracker.listener.revision');
        $this->assertEquals('henk', $definition->getArgument(1));
    }

    public function testLoadBlamable()
    {
        $this->container->setParameter('kernel.bundles', ['SecurityBundle' => 'BlahBlahClass']);
        $configs = ['entity_tracker' => ['blamable' => ['provider' => 'henk', 'default_username' => 'eux']]];
        $this->ext
            ->expects($this->once())
            ->method('validateComponent')
            ->with('Hostnet\Component\EntityBlamable\Blamable', 'blamable');

        $this->ext->load($configs, $this->container);
        $definition = $this->container->getDefinition('entity_tracker.listener.blamable');
        $this->assertEquals('henk', $definition->getArgument(1));
        $default_username_definition = $this->container->getDefinition(Configuration::BLAMABLE_DEFAULT_PROVIDER);
        $this->assertEquals('eux', $default_username_definition->getArgument(1));
    }

    public function testLoadMutation()
    {
        $configs = ['entity_tracker' => ['mutation' => []]];

        $this->ext
            ->expects($this->once())
            ->method('validateComponent')
            ->with('Hostnet\Component\EntityMutation\Mutation', 'mutation');

        $this->ext->load($configs, $this->container);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testValidateComponentClassFails()
    {
        $this->validateComponent('Henk', 'henk');
    }

    /**
     * No exception means it's correct
     */
    public function testValidateComponentClassSuccess()
    {
        $this->assertNull($this->validateComponent(get_class($this), 'henk'));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testValidateClassFails()
    {
        $this->assertNull($this->validateClass(get_class($this), 'henk'));
    }

    /**
     * No exception means it's correct
     */
    public function testValidateClassSuccess()
    {
        $this->validateClass('Henk', 'henk');
    }

    /**
     * @param string $annotation
     * @param string $config
     */
    private function validateComponent($annotation, $config)
    {
        $ext    = new HostnetEntityTrackerExtension();
        $class  = new \ReflectionClass(get_class($ext));
        $method = $class->getMethod('validateComponent');
        $method->setAccessible(true);
        $method->invoke($ext, $annotation, $config);
    }

    /**
     * @param string $annotation
     * @param string $config
     */
    private function validateClass($annotation, $config)
    {
        $ext    = new HostnetEntityTrackerExtension();
        $class  = new \ReflectionClass(get_class($ext));
        $method = $class->getMethod('validateClass');
        $method->setAccessible(true);
        $method->invoke($ext, $annotation, $config);
    }
}
