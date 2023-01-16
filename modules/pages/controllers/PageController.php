<?php
namespace app\modules\pages\controllers;

use app\models\Menu;
use Yii;
use yii\easyii\components\Controller;
use app\models\Page;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class PageController extends Controller
{

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Page::find(),
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }
        
    public function actionCreate()
    {
        $page = new Page();
        $menus = Menu::findAll(['active' => 1]);
        $menus = ArrayHelper::map( $menus , 'id', 'name' );

        return $this->render('edit', [
            'page' => $page,
            'menus' => $menus
        ]);

    }


    public function actionSave()
    {
        if($id = intval(Yii::$app->request->post('id'))) {
            if(!$page = Page::findOne($id))
                throw new NotFoundHttpException("такой страницы нет");
        } else
            $page = new Page();

        if($page->load(Yii::$app->request->post()) && $page->validate())
        {
            $page->save();
            $this->flash('success', "Страница сохранена");
            return $this->redirect(Url::to(['index']));
        }
        return $this->render('edit');
    }


    public function actionUpdate($id)
    {
        $model = Page::findOne($id);
        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect('index');
        }

        $menus = Menu::findAll(['active' => 1]);
        $menus = ArrayHelper::map( $menus , 'id', 'name' );

        return $this->render('edit', [
            'page' => $model,
            'menus' => $menus
        ]);

    }

    public function actionDelete($id)
    {
        if(($model = Page::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse('Страница удалена');
    }
}