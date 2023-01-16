<?php

namespace app\controllers;

use app\classes\Search;
use Yii;
use yii\base\Exception;
use yii\web\Response;
use app\classes\Deferred;
use yii\web\Controller;


class SearchController extends Controller
{
    public function actionIndex($search = '')
    {
        $products = (new Search())->findProducts($search)->query->all();
        return $this->render('index', [ 'products' => $products ]);
    }

    public function actionAjax($string = '')
    {
        if(!Yii::$app->request->isAjax)
            return 'restricted access';

        Yii::$app->response->format = Response::FORMAT_JSON;

        try{

            $string = trim($string);

            if(strlen($string) < 2)
                throw new Exception("слишком короткая строка");

            $content = (new Search())->findProducts($string)->forDropdownList();

            return [
                'error' => false,
                'message' => '',
                'content' => $content
            ];
        }
        catch(Exception $e)
        {
            return [ 'error' => true, 'message' => $e->getMessage(), 'content' => '' ];
        }

    }


}