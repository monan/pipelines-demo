<?php

namespace NickVeenhof\Hmac;

use Psr\Http\Message\RequestInterface;

interface RequestAuthenticatorInterface
{
    /**
     * Authenticates the passed request.
     *
     * @param \Psr\Http\Message\RequestInterface $request
     *
     * @throws \NickVeenhof\Hmac\Exception\InvalidSignatureException
     *   When the signature in the request does not match what's calculated.
     * @throws \NickVeenhof\Hmac\Exception\TimestampOutOfRangeException
     *   When the request timestamp is out of range of the server time.
     * @throws \NickVeenhof\Hmac\Exception\KeyNotFoundException
     *   When the key loader cannot find the key for the request ID.
     *
     * @return \NickVeenhof\Hmac\KeyInterface
     *   The key associated with the ID specified in the request.
     */
    public function authenticate(RequestInterface $request);
}
