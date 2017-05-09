<?php

namespace NickVeenhof\Hmac\Test\Symfony;

use NickVeenhof\Hmac\KeyInterface;
use NickVeenhof\Hmac\Symfony\HmacToken;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tests the Symfony authentication token.
 */
class HmacTokenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Ensures the getters work as expected.
     */
    public function testGetters()
    {
        $request = $this->getMock(Request::class);
        $key = $this->getMock(KeyInterface::class);

        $token = new HmacToken($request, $key);

        $this->assertEquals($request, $token->getRequest());
        $this->assertEquals($key, $token->getCredentials());
    }
}
