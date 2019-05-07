<?php
/**
 * @copyright 2019-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Bundle\EntityTrackerBundle\Functional;

use Hostnet\Bundle\EntityTrackerBundle\Functional\Fixtures\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @coversNothing
 */
class FunctionalTest extends KernelTestCase
{
    protected function setUp(): void
    {
        static::bootKernel();
    }

    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    public function testBlamableProviderExists(): void
    {
        self::assertNotNull(self::$kernel->getContainer()->get('entity_tracker.resolver.blamable.public'));
    }
}
