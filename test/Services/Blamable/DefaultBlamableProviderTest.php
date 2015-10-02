<?php
namespace Hostnet\Bundle\EntityTrackerBundle\Services\Blamable;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @covers Hostnet\Bundle\EntityTrackerBundle\Services\Blamable\DefaultBlamableProvider
 * @author Eunice Valdez <evaldez@hostnet.nl>
 */
class DefaultBlamableProviderTest extends \PHPUnit_Framework_TestCase
{
    private $token_storage;

    protected function setUp()
    {
        $this->token_storage = $this->getMock(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
    }

    public function testGetters()
    {
        $blamable_provider = new DefaultBlamableProvider($this->token_storage, "provided_user");
        $this->assertEquals("provided_user", $blamable_provider->getUpdatedBy());

        $token = new UsernamePasswordToken("phpunit", "hostnet", "thisisakey");
        $this->token_storage->expects($this->once())->method("getToken")->willReturn($token);
        $this->assertEquals("phpunit", $blamable_provider->getUpdatedBy());
        $this->assertInstanceOf('\DateTime', $blamable_provider->getChangedAt());
    }
}
