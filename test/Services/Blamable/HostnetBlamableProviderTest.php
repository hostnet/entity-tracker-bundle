<?php
namespace Hostnet\Bundle\EntityTrackerBundle\Services\Blamable;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @covers Hostnet\Bundle\EntityTrackerBundle\Services\Blamable\HostnetBlamableProvider
 * @author Eunice Valdez <evaldez@hostnet.nl>
 */
class HostnetBlamableProviderTest extends \PHPUnit_Framework_TestCase
{
    private $token_storage;

    protected function setUp()
    {
        $this->token_storage = $this->getMock(TokenStorageInterface::class);
    }

    public function testGetters()
    {
        $blamable_provider = new HostnetBlamableProvider($this->token_storage, "provided_user");
        $this->assertEquals("provided_user", $blamable_provider->getUpdatedBy());

        $token = new UsernamePasswordToken("phpunit", "hostnet", "thisisakey");
        $this->token_storage->expects($this->once())->method("getToken")->willReturn($token);
        $this->assertEquals("phpunit", $blamable_provider->getUpdatedBy());
        $this->assertTrue($blamable_provider->getChangedAt() instanceof \DateTime);
    }
}
