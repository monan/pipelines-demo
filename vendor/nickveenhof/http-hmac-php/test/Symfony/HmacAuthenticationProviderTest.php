<?php

namespace NickVeenhof\Hmac\Test\Symfony;

use NickVeenhof\Hmac\Key;
use NickVeenhof\Hmac\KeyInterface;
use NickVeenhof\Hmac\RequestAuthenticatorInterface;
use NickVeenhof\Hmac\Symfony\HmacAuthenticationProvider;
use NickVeenhof\Hmac\Symfony\HmacToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

/**
 * Tests the Symfony authentication provider.
 */
class HmacAuthenticationProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Ensures the authentication provider creates a signed token if auth passes.
     */
    public function testAuthentication()
    {
        $authId     = 'efdde334-fe7b-11e4-a322-1697f925ec7b';
        $authSecret = 'W5PeGMxSItNerkNFqQMfYiJvH14WzVJMy54CPoTAYoI=';

        $authenticator = $this->getMock(RequestAuthenticatorInterface::class);

        $request = Request::create('http://example.com');
        $key     = new Key($authId, $authSecret);

        $authenticator->expects($this->any())
            ->method('authenticate')
            ->will($this->returnValue($key));

        $token    = new HmacToken($request);
        $provider = new HmacAuthenticationProvider($authenticator);

        $response = $provider->authenticate($token);

        $this->assertInstanceOf(HmacToken::class, $response);
        $this->assertInstanceOf(KeyInterface::class, $response->getCredentials());
        $this->assertEquals($authId, $response->getCredentials()->getId());
        $this->assertEquals($authSecret, $response->getCredentials()->getSecret());
        $this->assertInstanceOf(Request::class, $response->getRequest());
    }

    /**
     * Ensures the authentication provider throws an exception if auth fails.
     *
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     * @expectedExceptionMessage Authentication failed.
     */
    public function testAuthenticationFailed()
    {
        $authenticator = $this->getMock(RequestAuthenticatorInterface::class);

        $authenticator->expects($this->any())
            ->method('authenticate')
            ->will($this->throwException(new \Exception('Authentication failed.')));

        $request  = Request::create('http://example.com');
        $token    = new HmacToken($request);
        $provider = new HmacAuthenticationProvider($authenticator);

        $provider->authenticate($token);
    }

    /**
     * Ensures the authentication provider only supports HMAC tokens.
     */
    public function testSupportsHmacTokens()
    {
        $request       = $this->getMock(Request::class);
        $authenticator = $this->getMock(RequestAuthenticatorInterface::class);

        $provider  = new HmacAuthenticationProvider($authenticator);
        $hmacToken = new HmacToken($request);
        $anonToken = new AnonymousToken('foo', 'foo');

        $this->assertTrue($provider->supports($hmacToken));
        $this->assertFalse($provider->supports($anonToken));
    }
}
