<?php

namespace Xuandan\Goods;

use SDK\Kernel\Support\Collection;

class Converter
{
    public static function convert(array $raw): array
    {
        $data = new Collection($raw);

        $couponUrl = $data->get('ActLink');
        $matchs = [];
        preg_match('#activityId=(\w+)#', $couponUrl, $matchs);
        $couponId = (string)($matchs[1] ?? null);
        preg_match('#sellerId=(\w+)#', $couponUrl, $matchs);
        $shopId = $matchs[1] ?? null;
        $productId = $data->get('GoodsId');

        return [
            'channel' => 'xuandan',
            'product' => [
                'id' => $productId,
                'shop_id' => $shopId,
                'category_id' => null,
                'title' => $data->get('LongGoodsName'),
                'short_title' => $data->get('GoodsName'),
                'desc' => $data->get('TjRemark'),
                'cover' => $data->get('ImgUrl'),
                'banners' => [],
                'sales_count' => (int)$data->get('SaleCount'),
                'rich_text_images' => [],
                'url' => $data->get('GoodsLink'),
            ],
            'coupon_product' => [
                'price' => (float)$data->get('LastPrice'),
                'original_price' => (float)$data->get('GoodsPrice'),
                'commission_rate' => (float)$data->get('TKMoneyRate'),
                'commission_amount' => (float)bcmul(
                    (float)$data->get('LastPrice'),
                    bcdiv(
                        (float)$data->get('TKMoneyRate'),
                        100,
                        2
                    ),
                    2
                ),
            ],
            'coupon' => [
                'id' => $couponId,
                'shop_id' => $shopId,
                'product_id' => $productId,
                'amount' => (float)$data->get('ActMoney'),
                'rule_text' => null,
                'stock' => (int)$data->get('Coupon_SaleCount'),
                'total' => (int)$data->get('Coupon_Count'),
                'started_at' => $data->get('BeginDate'),
                'ended_at' => $data->get('EndDate'),
                'url' => $couponUrl,
                'raw' => $raw,
            ],
            'shop' => [
                'id' => $shopId,
                'logo' => null,
                'name' => $data->get('ShopName'),
                'type' => $data->get('ly') == 1 ? 'tmall' : 'taobao',
            ]
        ];
    }
}