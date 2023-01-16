<?php
/**
 * Created by PhpStorm.
 * User: hust
 * Date: 23.04.2017
 * Time: 23:27
 */

namespace app\classes;

use app\models\Model;
use app\models\Product;
use yii\base\Object;

class Search extends Object
{
    public $query;
    public $search;

    public function findProducts($string)
    {
        $this->search = trim($string);

        $models = Model::find()->select('id')
            ->where(['like', 'vendorcode', $this->search])
            ->orWhere(['like', 'description', $this->search])
            ->orWhere(['like', 'name', $this->search])
            ->andWhere(['active' => 1]);

		
        $products = Product::find()->where(['model_id' => $models])->groupBy('model_id')->orderBy('model_id DESC')->limit('20');

        $this->query = $products;

        return $this;
    }

    public function forDropdownList()
    {
        $limit = 5;

        $result = $this->query->limit($limit)->all();

        $content = '';
        foreach ($result as $item)
        {
            $content .= '
			<li class="searchitemlist">
				<a href="'.$item->link.'" class="clearfix">
					<div class="searchitemlist_img" style="background-image:url('.$item->image->small.')"></div>
					<div class="searchitemlist_name">
						<div class="table">
							<div class="table_cell">
								'.$item->model->name.' '.$item->model->vendorcode.'
							</div>
						</div>
					</div>
				</a>
			</li>
			';
        }

        return $content;
    }








}