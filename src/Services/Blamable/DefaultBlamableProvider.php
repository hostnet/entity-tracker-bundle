<?php
/**
 * @copyright 2015-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Bundle\EntityTrackerBundle\Services\Blamable;

use Hostnet\Component\EntityBlamable\Provider\BlamableProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Provides the logged username and the current time for the entities that will be using the blamable component.
 */
class DefaultBlamableProvider implements BlamableProviderInterface
{
    public function __construct(
        private TokenStorageInterface $token_storage,
        private string $username
    ) {
    }

    #[\Override]
    public function getUpdatedBy(): string
    {
        if (($token = $this->token_storage->getToken()) instanceof TokenInterface) {
            return $token->getUserIdentifier();
        }

        return $this->username;
    }

    #[\Override]
    public function getChangedAt(): \DateTime
    {
        return new \DateTime();
    }
}
