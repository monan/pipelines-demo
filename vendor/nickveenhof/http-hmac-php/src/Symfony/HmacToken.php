<?php

namespace NickVeenhof\Hmac\Symfony;

use NickVeenhof\Hmac\KeyInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * An authentication token for HTTP HMAC requests.
 */
class HmacToken extends AbstractToken
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     *   The authenticated request.
     */
    protected $request;

    /**
     * @var \NickVeenhof\Hmac\KeyInterface
     *   The authenticated credentials.
     */
    protected $key;

    /**
     * Initializes the token.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *   The authenticated request.
     * @param \NickVeenhof\Hmac\KeyInterface $key
     *   An optional set of authenticated credentials.
     * @param \Symfony\Component\Security\Core\Role\RoleInterface[]|string[] $roles
     *   An array of roles.
     */
    public function __construct(Request $request, KeyInterface $key = null, array $roles = [])
    {
        parent::__construct($roles);

        $this->request = $request;
        $this->key = $key;
    }

    /**
     * Returns the authenticated request associated with the token.
     *
     * @return \Symfony\Component\HttpFoundation\Request
     *   The authenticated request.
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * {@inheritDoc}
     */
    public function getCredentials()
    {
        return $this->key;
    }
}
