<?php
/**
 * @copyright 2015-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Bundle\EntityTrackerBundle\Services\Blamable;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @covers \Hostnet\Bundle\EntityTrackerBundle\Services\Blamable\DefaultBlamableProvider
 */
class DefaultBlamableProviderTest extends TestCase
{
    use ProphecyTrait;

    private $token_storage;

    protected function setUp(): void
    {
        $this->token_storage = $this->createMock(TokenStorageInterface::class);
    }

    public function testGetters(): void
    {
        $blamable_provider = new DefaultBlamableProvider($this->token_storage, 'provided_user');
        self::assertEquals('provided_user', $blamable_provider->getUpdatedBy());

        $user = $this->prophesize(UserInterface::class);
        $user->getUserIdentifier()->willReturn('phpunit');
        $token = new UsernamePasswordToken($user->reveal(), 'hostnet', ['thisisakey']);
        $this->token_storage->expects(self::once())->method('getToken')->willReturn($token);

        self::assertEquals('phpunit', $blamable_provider->getUpdatedBy());
        self::assertNotNull($blamable_provider->getChangedAt());
    }
}
