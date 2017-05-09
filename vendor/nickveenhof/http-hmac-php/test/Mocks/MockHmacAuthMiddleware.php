<?php

namespace NickVeenhof\Hmac\Test\Mocks;

use NickVeenhof\Hmac\AuthorizationHeader;
use NickVeenhof\Hmac\Digest\Digest;
use NickVeenhof\Hmac\Guzzle\HmacAuthMiddleware;
use NickVeenhof\Hmac\KeyInterface;

/**
 * Allows the signing of requests with a custom authorization header.
 */
class MockHmacAuthMiddleware extends HmacAuthMiddleware
{
    /**
     * Initializes the middleware with a key, realm, and custom auth header.
     *
     * @param \NickVeenhof\Hmac\KeyInterface $key
     *   The key to sign requests with.
     * @param string $realm
     *   The API realm/provider.
     * @param array $customHeaders
     *   Custom headers to use in the signature.
     * @param \NickVeenhof\Hmac\AuthorizationHeaderInterface $authHeader
     *   The custom authorization header.
     */
    public function __construct(KeyInterface $key, $realm, array $customHeaders, AuthorizationHeader $authHeader)
    {
        parent::__construct($key, $realm);

        $this->requestSigner = new MockRequestSigner($key, $realm, new Digest(), $authHeader);
    }
}
