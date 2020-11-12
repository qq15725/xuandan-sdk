<?php

namespace Xuandan\Goods;

use SDK\Kernel\BaseClient;

class GoodsClient extends BaseClient
{
    /**
     * 商品列表
     *
     * @param int $page
     * @param int $perPage
     * @param array $query
     *
     * @link http://www.xuandan.com/DataApiv2.html
     *
     * @return array
     */
    public function list(int $page = 1, int $perPage = 100, array $query = [])
    {
        $query += [
            'page' => $page,
            'pagesize' => $perPage,
        ];

        return $this->httpGet('DataApi/index', $query);
    }

    /**
     * @param $id
     *
     * @link http://www.xuandan.com/DataApiv2.html
     *
     * @return array
     */
    public function find($id)
    {
        return $this->httpGet('DataApi/item', [
            'id' => $id
        ]);
    }
}