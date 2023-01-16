<?php

namespace app\controllers;

use app\models\Page;
use Yii;
use yii\web\Controller;

class PageController extends Controller
{
    function actionIndex($slug = '')
    {
        $page = Page::findOne([ 'slug' => $slug ]);

        #avtorkoda 16-08-2017
        \Yii::$app->view->title= trim($page->attributes['meta_title']) ? $page->attributes['meta_title'] : $page->attributes['caption'];
        \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $page->attributes['meta_description']]);
        \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => $page->attributes['meta_keywords']]);

        return $this->render('index' , [ 'page' => $page ] );
    }

	
	function actionRedirect($slug = ''){
		return $this->redirect('/service/'.$slug);
	}

}