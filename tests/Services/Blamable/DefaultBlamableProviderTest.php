<?php
/**
 * @copyright 2015-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Bundle\EntityTrackerBundle\Services\Blamable;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @covers \Hostnet\Bundle\EntityTrackerBundle\Services\Blamable\DefaultBlamableProvider
 */
class DefaultBlamableProviderTest extends TestCase
{
    private $token_storage;

    protected function setUp(): void
    {
        $this->token_storage = $this->createMock(TokenStorageInterface::class);
    }

    public function testGetters(): void
    {
        $blamable_provider = new DefaultBlamableProvider($this->token_storage, 'provided_user');
        self::assertEquals('provided_user', $blamable_provider->getUpdatedBy());

        $token = new UsernamePasswordToken('phpunit', 'hostnet', 'thisisakey');
        $this->token_storage->expects(self::once())->method('getToken')->willReturn($token);

        self::assertEquals('phpunit', $blamable_provider->getUpdatedBy());
        self::assertNotNull($blamable_provider->getChangedAt());
    }
}
