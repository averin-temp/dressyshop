<?php

namespace app\controllers;

use app\classes\QueryFilter;
use app\classes\Utilities;
use app\models\Answers;
use app\models\Filters;
use app\models\Model;
use app\models\Brand;
use app\models\Property;
use app\models\AutoBadge;
use app\models\PropertyType;
use app\models\Reviews;
use Yii;
use app\classes\CatalogUrl;
use app\models\Image;
use app\models\Product;
use app\classes\LastViewed;
use app\models\Size;
use app\models\Category;
use app\models\Color;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\data\Pagination;
use app\models\Questions;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class CatalogController extends Controller
{
    public function actionIndex($category = '')
    {
		
		//die($_SERVER['HTTP_REFERER']);

		//Yii::$app->session->remove('filters');

			//die(var_dump(Yii::$app->session->get('filters')));
        if ($category_id = CatalogUrl::findByPath($category))
        {
            $category = Category::findOne($category_id);
            $subquery = $category->allModelsQuery();// IDs
        }

        else
            $subquery = Model::find()->select('id');

        $avalibleProperties = QueryFilter::avalibleProperties($subquery);


		//echo "<pre>";
		//die(var_dump($subquery));
        $filters = Filters::findAll(['enable' => 1]);

        if ($options = Yii::$app->session->get('filters')) {
            $queryFilter = new QueryFilter([
                'options' => $options,
                'filters' => $filters,
                'avalible' => $avalibleProperties,
            ]);
            $queryFilter->apply($subquery);
        }



        $query = Product::find()
            ->where(['model_id' => $subquery, 'model.active' => 1])
            ->joinWith('model')
	        ->groupBy('model_id')
            ->orderBy('id DESC');

			//Utilities::debug($query);
// echo '<pre>';
// die(var_dump($filters));

        // сортировка
        if ($sort = Yii::$app->request->get('sort')) {
            $sortParams = [
                'price-desc' => ['model.final_price' => SORT_DESC],
                'price-asc' => ['model.final_price' => SORT_ASC],
                'delivery-desc' => ['model.delivery' => SORT_DESC],
                'delivery-asc' => ['model.delivery' => SORT_ASC],
                'latest-desc' => ['model.added' => SORT_DESC],
                'latest-asc' => ['model.added' => SORT_ASC],
                'vendorcode-asc' => ['model.vendorcode' => SORT_ASC],
                'vendorcode-desc' => ['model.vendorcode' => SORT_DESC],
            ];

            if (isset($sortParams[$sort])) {
                $query->orderBy($sortParams[$sort]);
            }
        }

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 44,
            ],
        ]);

        $models = $provider->getModels();

        #avtorkoda 16-08-2017
        /*$Cat = explode("/", $this->actionParams['category']);
        $Cat = array_diff($Cat, array(''));
        $Cat = $Cat[sizeof($Cat)-1];
        $Cat = Category::find()
            ->where(['slug' => $Cat])
            ->One();

        */
        if($Cat = Category::findOne($category_id))
        {
            #avtorkoda 16-08-2017
            \Yii::$app->view->title= trim($Cat->attributes['meta_title']) ? $Cat->attributes['meta_title'] : $Cat->attributes['caption'];
            \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $Cat->attributes['meta_description']]);
            \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => $Cat->attributes['meta_keywords']]);
        }

	

// echo '<pre>';	
// var_dump($provider);
// echo '</pre>';
// die();


        return $this->render('catalog', [
            'items' => $models,
            'resultsCount' => $provider->getTotalCount(),
            'pagination' => $provider->pagination,
            'filters' => $filters,
            'options' => $options,
            'avalibleProperties' => $avalibleProperties,
            'categoryCaption' => '',
            'category' => $category ? $category : false
        ]);
    }

    public function actionBrand($slug = '')
    {
		
        $id = Brand::find()->where(['slug'=>$slug])->one()['id'];
		$bname = Brand::find()->where(['slug'=>$slug])->one()['name'];
		//die(var_dump($id));
        $query = Product::find()
            ->joinWith('model')
            ->select('product.*, model.added')
            ->where('model.brand_id = '.$id)
            ->orderBy('model.added DESC')
            ->groupBy('model_id');

        // сортировка
        if ($sort = Yii::$app->request->get('sort')) {
            $sortParams = [
                'price-desc' => ['model.final_price' => SORT_DESC],
                'price-asc' => ['model.final_price' => SORT_ASC],
                'delivery-desc' => ['model.delivery' => SORT_DESC],
                'delivery-asc' => ['model.delivery' => SORT_ASC],
                'latest-desc' => ['model.added' => SORT_DESC],
                'latest-asc' => ['model.added' => SORT_ASC],
                'vendorcode-asc' => ['model.vendorcode' => SORT_ASC],
                'vendorcode-desc' => ['model.vendorcode' => SORT_DESC],
            ];

            if (isset($sortParams[$sort])) {
                $query->joinWith('model');
                $query->orderBy($sortParams[$sort]);
            }
        }

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 16,
            ],
        ]);

        $models = $provider->getModels();

		\Yii::$app->view->title= $bname;
        return $this->render('brand', [
            'items' => $models,
            'name' => $bname,
            'resultsCount' => $provider->getTotalCount(),
            'pagination' => $provider->pagination
        ]);
    }

    public function actionLatest()
    {
		$latest_days = AutoBadge::findOne(1)['days'];
        $query = Product::find()
            ->joinWith('model')
            ->select('product.*, model.added')
			->where('model.added > NOW() - INTERVAL '.$latest_days.' DAY')
            ->orderBy('model.added DESC')
            ->groupBy('model_id');

        // сортировка
        if ($sort = Yii::$app->request->get('sort')) {
            $sortParams = [
                'price-desc' => ['model.final_price' => SORT_DESC],
                'price-asc' => ['model.final_price' => SORT_ASC],
                'delivery-desc' => ['model.delivery' => SORT_DESC],
                'delivery-asc' => ['model.delivery' => SORT_ASC],
                'latest-desc' => ['model.added' => SORT_DESC],
                'latest-asc' => ['model.added' => SORT_ASC],
                'vendorcode-asc' => ['model.vendorcode' => SORT_ASC],
                'vendorcode-desc' => ['model.vendorcode' => SORT_DESC],
            ];

            if (isset($sortParams[$sort])) {
                $query->joinWith('model');
                $query->orderBy($sortParams[$sort]);
            }
        }

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 44,
            ],
        ]);

        $models = $provider->getModels();


        return $this->render('latest', [
            'items' => $models,
            'resultsCount' => $provider->getTotalCount(),
            'pagination' => $provider->pagination
        ]);
    }

    function actionReview($id = '')
    {

        $id = intval($id);
        $product = Product::find()
            ->where(['id' => $id])
            ->One();

        if (empty($product))
            throw new NotFoundHttpException();

        $review = new Reviews(['scenario' => Reviews::SCENARIO_CREATE, 'model_id' => $product->model_id]);
        if ($review->load(Yii::$app->request->post())) {
            if ($review->save()) {
                //Yii::$app->session->setFlash('review_message', 'Ваш отзыв будет опубликован после модерации.');
            }
            else
            {
                Yii::$app->session->setFlash('review_message_error', 'Отзыв не отправлен');
            }
        }
		Utilities::addNotice('25');
        //return $this->redirect(['product', 'id' => $id ]);
    }

    function actionQuestion($id = '')
    {

        $id = intval($id);
        $product = Product::find()
            ->where(['id' => $id])
            ->One();

        if (empty($product))
            throw new NotFoundHttpException();


        $question = new Questions(['scenario' => Questions::SCENARIO_CREATE, 'model_id' => $product->model_id]);
        if ($question->load(Yii::$app->request->post())) {
            if ($question->save()) {
                if ($question->save()) {
                    //Yii::$app->session->setFlash('question_message', '<span style="    color: #e64c65;
    //font-family: helveticaneuecyrbold;">Ваш вопрос принят. После модерации он будет опубликован с ответом менеджера.</span><br><br>');
                }
                else
                {
                    //Yii::$app->session->setFlash('question_message_error', 'Вопрос не отправлен');
                }
            }
        }

		Utilities::addNotice('44');
        //return $this->redirect(['product', 'id' => $id ]);
    }

	public function actionRedirect_product($id = ''){
		//die('sd');
		$id = intval($id);
		$model_mid = Product::find()->where(['id'=>$id])->one()->model_id;
		$prodslug = Model::find()->where(['id'=>$model_mid])->one()->slug;
		return $this->redirect('/'.$prodslug);
	}
    public function actionProduct($slug = '')
    {
		$model_mid = Model::find()->where(['slug'=>$slug])->one()->id;
		$pr_id = Product::find()->where(['model_id'=>$model_mid])->one()->id;
		//Utilities::debug($pr_id);
        $id = intval($pr_id);
        $product = Product::find()
            ->where(['id' => $id])
            ->One();

        if (empty($product))
            throw new NotFoundHttpException();

        $activeTab = 'description';

        $questionMessage = Yii::$app->session->getFlash('question_message');
        $questionMessageError = Yii::$app->session->getFlash('question_message_error');
        $question = new Questions(['scenario' => Questions::SCENARIO_CREATE, 'model_id' => $product->model_id]);
        if ($questionMessage != '' || $questionMessageError != '') {
            $activeTab = 'question';
        }

        $reviewMessage = Yii::$app->session->getFlash('review_message');
        $reviewMessageError = Yii::$app->session->getFlash('review_message_error');
        $review = new Reviews(['scenario' => Reviews::SCENARIO_CREATE, 'model_id' => $product->model_id]);
        if ($reviewMessage != '' || $reviewMessageError != '') {
            $activeTab = 'review';
        }

        $questions = Questions::find()->where(['model_id' => $product->model_id])->andWhere(['!=', 'answer', ''])->all();
        $reviews = Reviews::findAll(['model_id' => $product->model_id, 'avalible' => 1]);

        if ($product) LastViewed::add($product);

        #avtorkoda 16-08-2017
        if($model = Model::find()
            ->where(['id' => $product->attributes['model_id']])
            ->One()) {
            #avtorkoda 16-08-2017
            \Yii::$app->view->title = trim($model->attributes['meta_title']) ? $model->attributes['meta_title'] : $model->attributes['name'];
            \Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $model->attributes['meta_description']]);
            \Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => $model->attributes['meta_keywords']]);
        }
		
		// echo '<pre>';
		// die(var_dump($imagesss));
        return $this->render("product", [
            "product" => $product,
            "question" => $question,
            'review' => $review,
            'tab' => $activeTab,
            'questions' => $questions,
            'reviews' => $reviews,
            'reviewMessage' => $reviewMessage,
            'reviewMessageError' => $reviewMessageError,
            'questionMessage' => $questionMessage,
            'questionMessageError' => $questionMessageError
        ]);
    }

    public function actionAjax_change()
    {
        $request = Yii::$app->request;

        $product_id = $request->post('product_id');
        $color_id = $request->post('color_id');

        if ($product_id && $color_id) {

            $prod = Product::findOne($product_id);
            $model_id = $prod->model_id;

            $product = Product::find()->where(['model_id' => $model_id, 'color_id' => $color_id])->One();

            if ($product) {

                $imagesContent = $this->renderPartial('images_list', ['product' => $product]);
                $sizes = $this->renderPartial('sizes', ['product' => $product]);

                $data = [
                    'imagesContent' => $imagesContent,
                    'product' => $product->id,
                    'sizes' => $sizes,
                    'vendorCode' => $product->model->vendorcode
                ];
                echo json_encode($data);
                return;
            }
        }

        echo json_encode(['error' => true, 'message' => 'что то пошло не так']);
    }


    public function actionFilters()
    {
        $filtersOptions = Yii::$app->request->post('filters');
        Yii::$app->session->set('filters', $filtersOptions);
        return $this->redirect(Yii::$app->request->post('link'));
    }
	
	

//    public function actionBrand($id)
//    {
//        $filtersOptions = Yii::$app->session->get('filters');
//        $filtersOptions[101][] = $id;
//        Yii::$app->session->set('filters', $filtersOptions);
//        return $this->redirect(['catalog/index']);
//    }


    public function actionAjax()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;


        } else {
            return 'неверный запрос';
        }

    }


}