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
    /**
     * @var TokenStorageInterface
     */
    private $token_storage;

    /**
     * @var string
     */
    private $username;

    public function __construct(TokenStorageInterface $token_storage, string $username)
    {
        $this->token_storage = $token_storage;
        $this->username      = $username;
    }

    public function getUpdatedBy(): string
    {
        if (($token = $this->token_storage->getToken()) instanceof TokenInterface) {
            return $token->getUsername();
        }

        return $this->username;
    }

    public function getChangedAt(): \DateTime
    {
        return new \DateTime();
    }
}
