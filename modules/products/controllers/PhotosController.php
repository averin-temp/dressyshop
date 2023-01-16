<?php

namespace app\modules\products\controllers;

use app\classes\Utilities;
use app\models\Image;
use app\models\Product;
use app\modules\products\models\ImageUpload;
use Yii;
use app\models\Color;
use app\models\Size;
use app\models\Category;
use app\models\Brand;
use app\models\Material;
use app\models\Badge;
use app\models\Model;
use yii\base\Exception;
use yii\easyii\components\Controller;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class PhotosController extends Controller
{
    function actionEdit($model)
    {
        if($model = Model::findOne($model))
        {
            return $this->render('edit', [
                'model' => $model,
                'colors' => Color::find()->orderBy('name')->all(),
                'categories' => Category::find()->all(),
                'brands' => Brand::find()->all(),
                'badges' => Badge::find()->all()
            ]);
        }
        $this->flash('error','товар не найден');
        return $this->redirect('/admin/products');

    }

    function actionUpload()
    {
        if(!Yii::$app->request->isAjax)
            throw new BadRequestHttpException('неверный формат запроса');
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new Image( ['scenario' => Image::SCENARIO_UPLOAD] );

        try{

            $model->image = UploadedFile::getInstanceByName('image');

            if($model->load(Yii::$app->request->post()) &&
                $model->validate() &&
                $model->createPictures() &&
                    $model->save(false)) {

                return [
                    'ok' => true,
                    'id' => $model->id,
                    'src' => $model->small,
                    'source' => $model->source
                ];
            }

            throw new Exception('Ошибка модели');
        }
        catch(Exception $e)
        {
            return [
                'ok' => false,
                'message' => $e->getMessage(),
                'errors' => $model->errors
            ];
        }
    }

    function actionSave()
    {
        try {
            $id = Yii::$app->request->post('model');
            $id = intval($id);

            $model = Model::findOne($id);

            $colors = Yii::$app->request->post('colors');
            $colors = json_decode($colors, false);

            if($colors === null || $model === null)
                throw new NotFoundHttpException("Неверные параметры");

            //-----------------------------

            $sizerange = $model->sizeRange;

            if(!$sizerange || !$sizerange->sizes )
            {
                // каждый продукт определяется только цветом
                $products = Product::find()->select(['color_id','id'])->where(['model_id' => $model->id])->indexBy('id')->asArray()->all();
                $colors = ArrayHelper::index($colors,'id');


                // Индексирует по цвету продукты,
                // на 1 цвет 1 продукт, остальные будут удалены
                $temp = [];
                foreach($products as $product)
                {
                    $color_id = $product['color_id'];
                    $temp[$color_id] = $product['id'];
                }

                // находит новые цвета
                $to_create_colors = [];
                foreach($colors as $color)
                {
                    if(!isset($temp[$color->id]))
                        $to_create_colors[] = $color->id;
                }

                // удаляет все цвета которых нет
                $to_delete_colors = [];
                foreach($temp as $color_id => $product_id)
                {
                    if(!isset($colors[$color_id]))
                    {
                        unset($temp[$color_id]);
                        $to_delete_colors[$color_id] = 1;
                }
                }
//                die(var_dump($to_delete_colors));
                // все продукты
                $products = array_keys($products);
                // все продукты, которые нужно оставить
                $safe_products = array_values($temp);
                // Продукты для удаления
                $to_delete_products = array_diff($products, $safe_products);
                //die(var_dump($to_delete_products));
                // Удаляем выбранные
                Product::deleteAll(['id' => $to_delete_products]);

                // Создаем новые продукты
                foreach($to_create_colors as $id)
                {
                    $p = new Product([ 'color_id' => $id,'model_id' => $model->id ]);
                    $p->save();
                }


                $to_delete_colors = array_keys($to_delete_colors);
                $to_delete_images = Image::findAll(['model_id' => $model->id, 'color_id' => $to_delete_colors ]);
                Utilities::removeImages( $to_delete_images );



            }
            else
            {
                $products = Product::findAll(['model_id' => $model->id]);

                // цветом и размером
                $color_sizes = [];
                foreach($colors as $color){

                    if(!count($color->sizes))
                        throw new Exception("Вы не указали размеры, сохранение не выполнено");

                    foreach($color->sizes as $size_id)
                        $color_sizes[$color->id][$size_id] = true;
                }

                $toDelete = [];
                foreach($products as $product)
                    if( isset($color_sizes[$product->color_id][$product->size_id]) )
                        unset($color_sizes[$product->color_id][$product->size_id]);
                    else $toDelete[] = $product->id;

                Product::deleteAll(['id' => $toDelete]);


                foreach($color_sizes as $color_id => $sizes)
                    foreach($sizes as $size_id => $true) {
                        $p = new Product([ 'color_id' => $color_id,  'size_id' => $size_id,  'model_id' => $model->id ]);
                        $p->save();
                    }


            }

            $images_all = Image::find()->select('id')->where(['model_id' => $model->id])->all();
            $images_all = ArrayHelper::getColumn($images_all, 'id');

            $images = [];
            foreach($colors as $color)
			{
				$imagesOrder = [];
				foreach($color->images as $image)
				{
					$images[] = $image;
					$imagesOrder[] = $image;
				}
				foreach($imagesOrder as $key => $item){
					if($key == 0 || $key == 1){
						if($key == 0){$ind = 'sq_first';}
						if($key == 1){$ind = 'sq_second';}
						$imaga_filename =  Image::findOne($item)->filename;
						$imaga_path = "https://dressyshop.ru/images/models/".$imaga_filename."_small.jpg";
						$image_width = getimagesize ($imaga_path)[0];
						$image_height = getimagesize ($imaga_path)[1];
						if($image_width >= $image_height){
							Image::updateAll([$ind => 1], ['id' => $item]);
						}
					}
					
					//die(var_dump($image_height));
					Image::updateAll(['order' => $key], ['id' => $item]);
				}
			}

            $toDelete = array_diff($images_all, $images);

            $to_delete_images = Image::findAll(['id' => $toDelete]);
            Utilities::removeImages($to_delete_images); 

            $primary = [];

            foreach($colors as $color){
                $primary[] = $color->primary;
            }


            //$primar = Image::find()->where(['model_id'=>$id])->andWhere(['primary'=>1])->one();

//            echo '<pre>';
//            $primar = Image::find()->where(['model_id'=>$id])->andWhere(['primary'=>1])->one();
//            var_dump($primar->id);
//            echo '</pre>';
//            die();

            Image::updateAll(['primary' => 0], ['id' => $images]);
            //Image::updateAll(['order' => 44], ['id' => $images]);
            Image::updateAll(['primary' => 1], ['id' => $primary]);
			
			

            $this->flash('success', "сохранено" );
        }
        catch (Exception $e) {
            $this->flash('error',$e->getMessage() );
        }

        return $this->back();
    }


    public function actionClip()
    {
        if(!Yii::$app->request->isAjax)
            throw new BadRequestHttpException('неверный формат запроса');
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = intval(Yii::$app->request->post('id'));

        try {

            if(!$model = Image::findOne($id))
                throw new Exception('Изображение не найдено');

            $model->scenario = Image::SCENARIO_CLIPPING;
            if($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->createMiniatures();
                return [ 'ok' => true, 'src' => $model->small ];
            }

            throw new Exception('Ошибка модели');
        }
        catch(Exception $e) {
            return [ 'ok' => false, 'message' => $e->getMessage() ];
        }
    }




}