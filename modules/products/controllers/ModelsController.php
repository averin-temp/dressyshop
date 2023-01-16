<?php
namespace app\modules\products\controllers;


use app\classes\Utilities;
use app\models\Product;
use app\models\Property;
use app\models\PropertyType;
use app\models\Questions;
use app\models\Reviews;
use Yii;
use app\models\Brand;
use app\models\Color;
use app\models\Image;
use app\models\Model;
use yii\base\Exception;
use yii\bootstrap\ActiveForm;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\Category;
use app\models\SizeRange;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\easyii\components\Controller;
use app\modules\products\models\BatchAction;
use app\modules\products\models\ProductsFilters;
use app\modules\products\models\ProductsTableSettings;
use app\models\Badge;




class ModelsController extends Controller
{

    public function actionBatch()
    {
        $action = new BatchAction();
        if($action->load(Yii::$app->request->post()))
        {
            if($action->validate())
                $action->Execute();
        }

        return $this->back();
    }

    public function actionIndex()
    {
        $settings = ProductsTableSettings::get();

        $get = Yii::$app->request->get();
        $post = Yii::$app->request->post();

        if($settings->load($post) ) {
            $settings->save();
        }

        $query = Model::find();

        $query->joinWith(['brand' => function($query) { $query->from(['brand' => 'brand']); }])->orderBy(['lastmodifed' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $settings->sort,
            'pagination' => []
        ]);

        $filters = new ProductsFilters();
        if($filters->load($get)) {
            $filters->validate();
            $filters->applyFilters($dataProvider->query);
        }


        $categories = Category::find()->all();
        $categories = ArrayHelper::map(  $categories  ,'id','caption');


        return $this->render('index', [
            'provider' => $dataProvider,
            'filters' => $filters,
            'settings' => $settings,
            'categories' => $categories
        ]);
    }


    /**
     *
     *
     * @return string|Response
     */
    public function actionSave()
    {
        $model_id = intval(Yii::$app->request->post('id'));

        try
        {
            if($model_id) {
                $model = Model::findOne($model_id);
                if(!$model)
                    throw new Exception('Товар не найден');
            }
            else
                $model = new Model();

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $model->scenario = Model::SCENARIO_AJAX;
                if($model->load(Yii::$app->request->post()))
                return ActiveForm::validate($model);
                else return array('empty parameters');
            }


            if($model->load(Yii::$app->request->post() )) {



                $model->setPurchasePrice($model->purchase_price);

                if($model->save()) {

                    $this->flash('success', 'Товар сохранен');
                }
            }
        }
        catch(Exception $e)
        {
            $this->flash('error', $e->getMessage());
            return $this->redirect('/admin/users');
        }


		//return $this->redirect('/admin/products/models/');
        return $this->render('edit', [
            'model' => $model,
            'sizeranges' => SizeRange::find()->all(),
            'categories' => Category::find()->all(),
            'brands' => Brand::find()->all(),
            'properties' => empty($properties) ? [] : $properties ,
            'property_types' => PropertyType::find()->all(),
            'colors' => Color::find()->all(),
            'badges' => Badge::find()->all()
        ]);
    }

    public function actionCreate()
    {
        $model = new Model();
        $model->active = 1;
        return $this->render('edit', [
            'model' => $model,
            'sizeranges' => SizeRange::find()->orderBy('order')->all(),
            'categories' => Category::find()->all(),
            'brands' => Brand::find()->all(),
            'properties' => [],
            'property_types' => PropertyType::find()->all(),
            'colors' => Color::find()->all(),
            'badges' => Badge::find()->all()
        ]);
    }

    public function actionCreate_cat($cat_id)
    {
        if(!Category::findOne($cat_id)){
            $this->flash('error', 'Такой категории нет');
            return $this->redirect('/admin/categories/index');
        }


        $model = new Model();
        $model->active = 1;
        $model->category_id = $cat_id;
        return $this->render('edit', [
            'model' => $model,
            'sizeranges' => SizeRange::find()->all(),
            'categories' => Category::find()->all(),
            'brands' => Brand::find()->all(),
            'properties' => [],
            'property_types' => PropertyType::find()->all(),
            'colors' => Color::find()->all(),
            'badges' => Badge::find()->all()
        ]);
    }

    public function actionUpload()
    {
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;

            if($id = Yii::$app->request->post('id'))
                $image = Image::findOne($id);
            else
                $image = new Image();

            if($image->load(Yii::$app->request->post()))
            {
                $colorID = Yii::$app->request->post('color_id');
                $colorLabel = Yii::$app->request->post('color_label');

                if(!$colorID){
                    $color = new Color();
                    if($colorLabel) $color->name = $colorLabel;
                    $color->save();
                    $colorID = $color->id;
                }

                $image->color_id = $colorID;

                if(!$image->id)
                    $image->UploadImage();

                if($image->save())
                    return [
                        'error' => false ,
                        'id' => $image->id,
                        'src' => $image->normal
                    ];

            }
        }
        return '';
    }

    public function actionEdit($id)
    {
        $id = intval($id);

        try
        {
            $model = Model::findOne($id);

            if(!$model) throw new Exception("Такого пользователя нет");

            // отсюда dropdownList берет значения,
            // оно должно быть как  [ id, id, id]
            $model->categories_id = ArrayHelper::getColumn($model->categories, 'id');

            $properties = Property::findAll(['model_id' => $model->id]);

            return $this->render('edit', [
                'model' => $model,
                'sizeranges' => SizeRange::find()->orderBy('order')->all(),
                'categories' => Category::find()->all(),
                'brands' => Brand::find()->all(),
                'properties' => $properties,
                'property_types' => PropertyType::find()->all(),
                'colors' => Color::find()->all(),
                'badges' => Badge::find()->all()
            ]);
        }
        catch(Exception $e)
        {
            $this->flash('error', $e->getMessage() );
            return $this->redirect('/admin/users');
        }
    }


    public function actionDelete($id)
    {
        if($model = Model::findOne($id)) {
            $model->delete();
            Reviews::deleteAll(['model_id' => $model->id]);
            Questions::deleteAll(['model_id' => $model->id]);
            $images = Image::findAll(['model_id' => $model->id]);
            Utilities::removeImages($images);
            Product::deleteAll(['model_id' => $model->id]);
        }
        else
        {
            $this->flash('error', 'Товар не найден');
        }
        return $this->back();
    }
}