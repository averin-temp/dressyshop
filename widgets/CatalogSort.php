<?php

namespace app\widgets;

use Yii;
use yii\bootstrap\Widget;
use yii\helpers\Url;

class CatalogSort extends Widget
{
    public function run()
    {
        $request = Yii::$app->getRequest();

        // для отладки
        $url = $request->url;
        $absoluteUrl = $request->absoluteUrl;
        $hostInfo = $request->hostInfo;
        $pathInfo = $request->pathInfo;
        $baseUrl = $request->baseUrl;
        $scriptUrl = $request->scriptUrl;
        $queryString = $request->queryString;


        $params = $request->getQueryParams();
        unset($params['category']);

        $sort = Yii::$app->request->get('sort');

        $fields = [
            'latest-desc' => 'по новизне',
            'price-asc' => 'по возрастанию цены',
            'price-desc' => 'по убыванию цены',
            'vendorcode-asc' => 'по артикулу А-Я',
            'vendorcode-desc' => 'по артикулу Я-А',
        ];

        return $this->render('catalog_sort', ['sort' => $sort, 'params' => $params, 'fields' => $fields, 'url' => $pathInfo ]);
    }
}