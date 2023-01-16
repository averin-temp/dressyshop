<?php

namespace app\widgets;

use yii\bootstrap\Widget;

class CatalogFilters extends Widget
{
    public $filters;

    public function run()
    {
        return '';//$this->render('filters', ['filters' => $this->filters] );
    }
}