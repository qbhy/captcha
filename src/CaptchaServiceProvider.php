<?php

namespace Mews\Captcha;

use Illuminate\Support\ServiceProvider;

/**
 * Class CaptchaServiceProvider
 * @package Mews\Captcha
 */
class CaptchaServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     *
     * @return null
     */
    public function boot()
    {
        // Publish configuration files
        $this->publishes([
            __DIR__ . '/../config/captcha.php' => config_path('captcha.php'),
        ], 'config');

        // HTTP routing
        if (strpos($this->app->version(), 'Lumen') !== false) {
            $this->app['router']->get('captcha/{codeKey}/[/{config}]',
                'Mews\Captcha\LumenCaptchaController@getCaptcha');
        } else {
            if ((double)$this->app->version() >= 5.2) {
                $this->app['router']->get('captcha/{config?}', '\Mews\Captcha\CaptchaController@getCaptcha')
                                    ->middleware('web');
            } else {
                $this->app['router']->get('captcha/{config?}', '\Mews\Captcha\CaptchaController@getCaptcha');
            }
        }

        // Validator extensions
        $this->app['validator']->extend('captcha', function ($attribute, $value, $parameters) {
            return captcha_check($value);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Merge configs
        $this->mergeConfigFrom(
            __DIR__ . '/../config/captcha.php', 'captcha'
        );

        // Bind captcha

        $codeKey =
            $this->app['request']->get('code_key') ?? $this->app['request']->header('code_key') ?? str_random(40);

        $this->app->bind(Captcha::class, function ($app) use ($codeKey) {
            return Captcha::instance($codeKey);
        });

        $this->app->alias(Captcha::class, 'captcha');
    }

}
