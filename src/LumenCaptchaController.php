<?php

namespace Mews\Captcha;

use Laravel\Lumen\Routing\Controller;

/**
 * Class CaptchaController
 * @package Mews\Captcha
 */
class LumenCaptchaController extends Controller
{

    /**
     * get CAPTCHA
     *
     * @param string $config
     *
     * @return \Intervention\Image\ImageManager->response
     */
    public function getCaptcha($codeKey, $config = 'default')
    {
        return Captcha::instance($codeKey)->create($config);
    }

}
