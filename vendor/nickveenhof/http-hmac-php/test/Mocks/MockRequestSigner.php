<?php

namespace NickVeenhof\Hmac\Test\Mocks;

use NickVeenhof\Hmac\AuthorizationHeaderInterface;
use NickVeenhof\Hmac\Digest\DigestInterface;
use NickVeenhof\Hmac\KeyInterface;
use NickVeenhof\Hmac\RequestSigner;
use Psr\Http\Message\RequestInterface;

/**
 * Allows the signing of requests with a custom authorization header.
 */
class MockRequestSigner extends RequestSigner
{
    /**
     * @var \NickVeenhof\Hmac\AuthorizationHeaderInterface
     *  A custom authorization header.
     */
    protected $authHeader;

    /**
     * Initializes the request signer with a key, realm, and auth header.
     *
     * @param \NickVeenhof\Hmac\KeyInterface $key
     *   The key to sign requests with.
     * @param string $realm
     *   The API realm/provider
     * @param \NickVeenhof\Hmac\Digest\DigestInterface $digest
     *   The message digest to use when signing requests.
     * @param \NickVeenhof\Hmac\AuthorizationHeaderInterface $authHeader
     *   The custom authorization header.
     */
    public function __construct(KeyInterface $key, $realm, DigestInterface $digest, AuthorizationHeaderInterface $authHeader)
    {
        parent::__construct($key, $realm, $digest);

        $this->authHeader = $authHeader;
    }

    /**
     * {@inheritDoc}
     */
    protected function buildAuthorizationHeader(RequestInterface $request, array $customHeaders = [])
    {
        return $this->authHeader;
    }
}
