<?php

namespace app\widgets;

use yii\widgets\Breadcrumbs;

class Breads extends Breadcrumbs
{
    public $path = array();
    public $home;
    public $last = '';

    public function run()
    {
        return $this->render('breadcrumbs', ['path' => $this->path, 'home' => $this->home, 'last' => $this->last]);
    }
}