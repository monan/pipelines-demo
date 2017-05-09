<?php

namespace NickVeenhof\Hmac\Symfony;

use NickVeenhof\Hmac\KeyLoaderInterface;
use NickVeenhof\Hmac\RequestAuthenticator;
use NickVeenhof\Hmac\Symfony\HmacAuthenticationEntryPoint;
use NickVeenhof\Hmac\Symfony\HmacAuthenticationListener;
use NickVeenhof\Hmac\Symfony\HmacAuthenticationProvider;
use Silex\Application;
use Silex\ServiceProviderInterface;
use NickVeenhof\Hmac\Symfony\HmacResponseListener;

/**
 * A Silex service provider to provide Acquia HTTP Hmac as a firewall option.
 */
class HmacSecurityProvider implements ServiceProviderInterface
{
    /**
     * @var \NickVeenhof\Hmac\KeyLoaderInterface
     *   An HMAC key loader.
     */
    protected $keyLoader;

    /**
     * Initializes the security provider.
     *
     * @param \NickVeenhof\Hmac\KeyLoaderInterface $keyLoader
     *   An HMAC key loader.
     */
    public function __construct(KeyLoaderInterface $keyLoader)
    {
        $this->keyLoader = $keyLoader;
    }

    /**
     * {@inheritDoc}
     */
    public function register(Application $app)
    {
        $keyLoader = $this->keyLoader;

        $app['security.authentication_listener.factory.hmac'] = $app->protect(function ($name, $options) use ($app) {

            if (!isset($app['security.authentication_provider.' . $name . '.hmac'])) {
                $app['security.authentication_provider.' . $name . '.hmac'] = $app['security.authentication_provider.hmac._proto']($name, $options);
            }

            if (!isset($app['security.authentication_listener.' . $name . '.hmac'])) {
                $app['security.authentication_listener.' . $name . '.hmac'] = $app['security.authentication_listener.hmac._proto']($name, $options);
            }

            if (!isset($app['security.entry_point.' . $name . '.hmac'])) {
                $app['security.entry_point.' . $name . '.hmac'] = $app['security.entry_point.hmac._proto']($name, $options);
            }

            return [
                'security.authentication_provider.' . $name . '.hmac',
                'security.authentication_listener.' . $name . '.hmac',
                'security.entry_point.' . $name . '.hmac',
                'pre_auth',
            ];
        });

        $app['security.hmac.response_listener'] = $app->share(function () {
            return new HmacResponseListener();
        });

        $app['security.authentication_provider.hmac._proto'] = $app->protect(function($name, $options) use ($app, $keyLoader) {
            return $app->share(function () use ($keyLoader) {
                return new HmacAuthenticationProvider(new RequestAuthenticator($keyLoader));
            });
        });

        $app['security.authentication_listener.hmac._proto'] = $app->protect(function($name, $options) use ($app) {
            return $app->share(function () use ($app, $name) {
                return new HmacAuthenticationListener(
                    $app['security.token_storage'],
                    $app['security.authentication_manager'],
                    $app['security.entry_point.' . $name . '.hmac']
                );
            });
        });

        $app['security.entry_point.hmac._proto'] = $app->protect(function($name, $options) use ($app) {
            return new HmacAuthenticationEntryPoint();
        });
    }

    /**
     * {@inheritDoc}
     */
    public function boot(Application $app)
    {
        if (!isset($app['security'])) {
            throw new \LogicException('You must register the SecurityServiceProvider to use the HmacServiceProvider.');
        }

        $app['dispatcher']->addSubscriber($app['security.hmac.response_listener']);
    }
}
