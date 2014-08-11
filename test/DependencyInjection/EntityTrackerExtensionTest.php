<?php
namespace Hostnet\Bundle\EntityTrackerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Test the DoctrineExtensionBundleExtension methods
 *
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @covers Hostnet\Bundle\EntityTrackerBundle\DependencyInjection\EntityTrackerExtension
 */
class EntityTrackerExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $ext;
    private $container;

    /**
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->ext = $this
            ->getMockBuilder('Hostnet\Bundle\EntityTrackerBundle\DependencyInjection\EntityTrackerExtension')
            ->setMethods(['validateComponent'])
            ->getMock();

        $this->container = $this
            ->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testLoadRevision()
    {
        $configs = ['entity_tracker' => ['revision' => ['factory' => 'henk']]];

        $definition = new Definition(null, ['piet', 'hans']);

        $this->container
            ->expects($this->once())
            ->method('getDefinition')
            ->with('entity_tracker.listener.revision')
            ->willReturn($definition);

        $this->ext->load($configs, $this->container);

        $this->assertEquals('henk', $definition->getArgument(1));
    }

    public function testLoadBlamable()
    {
        $configs = ['entity_tracker' => ['blamable' => ['provider' => 'henk']]];

        $definition = new Definition(null, ['piet', 'hans']);

        $this->ext
            ->expects($this->once())
            ->method('validateComponent')
            ->with('Hostnet\Component\EntityBlamable\Blamable', 'blamable');

        $this->container
            ->expects($this->once())
            ->method('getDefinition')
            ->with('entity_tracker.listener.blamable')
            ->willReturn($definition);

        $this->ext->load($configs, $this->container);

        $this->assertEquals('henk', $definition->getArgument(1));
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
     * @param string $annotation
     * @param string $config
     */
    private function validateComponent($annotation, $config)
    {
        $ext    = new EntityTrackerExtension();
        $class  = new \ReflectionClass(get_class($ext));
        $method = $class->getMethod('validateComponent');
        $method->setAccessible(true);
        $method->invoke($ext, $annotation, $config);
    }
}
