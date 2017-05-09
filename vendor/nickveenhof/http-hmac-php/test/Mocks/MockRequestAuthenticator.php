<?php

namespace NickVeenhof\Hmac\Test\Mocks;

use NickVeenhof\Hmac\AuthorizationHeaderInterface;
use NickVeenhof\Hmac\Digest\Digest;
use NickVeenhof\Hmac\KeyInterface;
use NickVeenhof\Hmac\KeyLoaderInterface;
use NickVeenhof\Hmac\RequestAuthenticator;

/**
 * Allows the authentication of requests with a custom authorization header.
 */
class MockRequestAuthenticator extends RequestAuthenticator
{
    /**
     * @var \NickVeenhof\Hmac\AuthorizationHeaderInterface
     *  A custom authorization header.
     */
    protected $authHeader;

    /**
     * @var int
     *   A custom timstamp by which to compare requests.
     */
    protected $timestamp;

    /**
     * Initializes the authenticator with a key loader, auth header, and comparison timestamp.
     *
     * @param \NickVeenhof\Hmac\KeyLoaderInterface $keyLoader
     *   A datastore used to locate secrets for corresponding IDs.
     * @param \NickVeenhof\Hmac\AuthorizationHeaderInterface $authHeader
     *   An optional custom authorization header.
     * @param int $timestamp
     *   An optional custom timestamp by which to compare requests.
     */
    public function __construct(KeyLoaderInterface $keyLoader, AuthorizationHeaderInterface $authHeader = null, $timestamp = null)
    {
        parent::__construct($keyLoader);

        $this->authHeader = $authHeader;
        $this->timestamp = $timestamp ?: time();
    }

    /**
     * {@inheritDoc}
     */
    protected function getCurrentTimestamp()
    {
        return $this->timestamp;
    }
}
