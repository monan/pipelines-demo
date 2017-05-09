<?php

namespace NickVeenhof\Hmac\Guzzle;

use NickVeenhof\Hmac\Exception\MalformedResponseException;
use NickVeenhof\Hmac\KeyInterface;
use NickVeenhof\Hmac\RequestSigner;
use NickVeenhof\Hmac\ResponseAuthenticator;
use Guzzle\Http\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HmacAuthMiddleware
{
    /**
     * @var \NickVeenhof\Hmac\KeyInterface
     *  The key with which to sign requests and responses.
     */
    protected $key;

    /**
     * @var \NickVeenhof\Hmac\RequestSignerInterface
     */
    protected $requestSigner;

    /**
     * @var array
     */
    protected $customHeaders = [];

    /**
     * @param \NickVeenhof\Hmac\KeyInterface $key
     * @param string $realm
     * @param array $customHeaders
     */
    public function __construct(KeyInterface $key, $realm = 'Acquia', array $customHeaders = [])
    {
        $this->key = $key;
        $this->customHeaders = $customHeaders;
        $this->requestSigner = new RequestSigner($key, $realm);
    }

    /**
     * Called when the middleware is handled.
     *
     * @param callable $handler
     *
     * @return \Closure
     */
    public function __invoke(callable $handler)
    {
        return function ($request, array $options) use ($handler) {

            $request = $this->signRequest($request);

            $promise = function (ResponseInterface $response) use ($request) {

                $authenticator = new ResponseAuthenticator($request, $this->key);

                if (!$authenticator->isAuthentic($response)) {
                    throw new MalformedResponseException(
                        'Could not verify the authenticity of the response.',
                        null,
                        0,
                        $response
                    );
                }

                return $response;
            };

            return $handler($request, $options)->then($promise);
        };
    }

    /**
     * Signs the request with the appropriate headers.
     *
     * @param \Psr\Http\Message\RequestInterface $request
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    public function signRequest(RequestInterface $request)
    {
        return $this->requestSigner->signRequest($request, $this->customHeaders);
    }
}
