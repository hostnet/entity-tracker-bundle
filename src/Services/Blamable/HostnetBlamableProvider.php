<?php
namespace Hostnet\Bundle\EntityTrackerBundle\Services\Blamable;

use Hostnet\Component\EntityBlamable\Provider\BlamableProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Provides the logged username and the current time for the entities that will be using the
 * blamable component.
 *
 * @author Eunice Valdez <evaldez@hostnet.nl>
 */
class HostnetBlamableProvider implements BlamableProviderInterface
{
    /**
     * @var string
     */
    private $application;

    /**
     * @var TokenStorageInterface
     */
    private $token_storage;

    /**
     * @param string $application
     * @param TokenStorageInterface $token_storage
     */
    public function __construct(TokenStorageInterface $token_storage, $application)
    {
        $this->token_storage = $token_storage;
        $this->application   = $application;
    }

    /**
     * @see \Hostnet\Component\EntityBlamable\Provider\BlamableProviderInterface::getUpdatedBy()
     */
    public function getUpdatedBy()
    {
        if (($token = $this->token_storage->getToken()) instanceof TokenInterface) {
            return $token->getUsername();
        } else {
            return $this->application;
        }
    }

    /**
     * @see \Hostnet\Component\EntityBlamable\Provider\BlamableProviderInterface::getChangedAt()
     */
    public function getChangedAt()
    {
        return new \DateTime();
    }
}
