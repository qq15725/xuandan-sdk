<?php

namespace Xuandan;

use SDK\Kernel\Support\Collection;

class Converter
{
    /**
     * 商品数据转换成统一的数据格式
     *
     * @param array $raw
     * @param null $apiType
     * @param bool $retainRaw
     *
     * @return array
     */
    public static function product(array $raw, $apiType = null, $retainRaw = true): array
    {
        if (!$raw) {
            return [];
        }

        if (isset($raw[0])) {
            foreach ($raw as &$itemRaw) {
                $itemRaw = self::product($itemRaw, $apiType, $retainRaw);
            }
            return $raw;
        }

        $data = new Collection($raw);

        $couponUrl = $data->get('ActLink');
        $matchs = [];
        preg_match('#activityId=(\w+)#', $couponUrl, $matchs);
        $couponId = (string)($matchs[1] ?? null);
        preg_match('#sellerId=(\w+)#', $couponUrl, $matchs);
        $shopId = $matchs[1] ?? null;
        $productId = $data->get('GoodsId');

        $data = [
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
            'coupons' => [
                [
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
                ]
            ],
            'shop' => [
                'id' => $shopId,
                'logo' => null,
                'name' => $data->get('ShopName'),
                'type' => $data->get('ly') == 1 ? 'tmall' : 'taobao',
            ]
        ];

        if ($retainRaw) {
            $data['raw'] = $raw;
        }

        return $data;
    }
}