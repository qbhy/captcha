<?php
/**
 * User: 96qbhy
 * Date: 2018/4/24
 * Time: 下午3:47
 */

namespace Mews\Captcha;

use SessionHandlerInterface;
use Illuminate\Session\CacheBasedSessionHandler;


class CaptchaStore extends \Illuminate\Session\Store
{
    /** @var CaptchaStore[] */
    protected static $codeKeyInstances = [];

    public function __construct(string $name, SessionHandlerInterface $handler, ?string $id = null)
    {
        parent::__construct($name, $handler, $name);
        $this->start();
    }

    public static function instance(string $codeKey, $minutes = 20)
    {
        if (!isset(static::$codeKeyInstances[$codeKey])) {
            static::$codeKeyInstances[$codeKey] = new static(
                $codeKey, new CacheBasedSessionHandler(app('cache.store'), $minutes)
            );
        }

        return static::$codeKeyInstances[$codeKey];
    }

}