<?php

namespace Xuandan;

use SDK\Kernel\ServiceContainer;

/**
 * Class Application.
 *
 * @link http://www.xuandan.com/DataApiv2.html
 *
 * @property \Xuandan\Goods\GoodsClient $goods 商品
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        Auth\ServiceProvider::class,
        Goods\ServiceProvider::class,
    ];

    /**
     * @var array
     */
    protected $defaultConfig = [
        'http' => [
            'timeout' => 10.0,
            'base_uri' => 'http://api.xuandan.com'
        ],
    ];

    public function __construct(
        string $appkey = null,
        array $config = [],
        array $prepends = []
    )
    {
        $config = array_merge([
            'appkey' => $appkey,
        ], $config);
        parent::__construct($config, $prepends);
    }
}