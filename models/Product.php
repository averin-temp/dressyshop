<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\Url;

class Product extends ActiveRecord
{

    /**
     * Возвращает свою модель
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModel()
    {
        return $this->hasOne(Model::className(), ['id' => 'model_id']);
    }

    /**
     * Возвращает объект Image главной картинки для этого продукта.
     * Если ее нет, первую попавшуюся.
     * Если картинок нету то пустую строку.
     *
     * @return array|null|ActiveRecord
     */
    public function getImage()
    {
        $image = Image::find()->where(["model_id" => $this->model_id, "color_id" => $this->color_id, "primary" => 1 ])->One();

        if(!$image){
            $image = Image::find()->where(["model_id" => $this->model_id, "color_id" => $this->color_id])->One();

            $image = empty($image) ? '' : $image ;
        }

        return $image;
    }

    /**
     * Возвращает изображения для этого продукта
     *
     * @return array|ActiveRecord[]
     */
    public function getImages()
    {
        return Image::find()->where(["model_id" => $this->model_id, "color_id" => $this->color_id ])->All();
    }

    /**
     * Возвращает ссылку на страницу продукта в каталоге сайта
     *
     * @return string
     */
    public function getLink(){
        return Url::to([ 'catalog/product', 'id' => $this->id ]);
    }

    /**
     * Возвращает объект Size для продукта
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
    }

    /**
     * Возвращает объект Color для продукта
     *
     * @return \yii\db\ActiveQuery
     */
    public function getColor()
    {
        return $this->hasOne(Color::className(), ['id' => 'color_id']);
    }

    public function getBadge()
    {
        $model = $this->model;
        $badge = $model->badge;
        if($badge){
            return $badge;
        }

        return AutoBadge::getBadge($this->model);
    }

    /**
     * Возвращает доступные размеры для этой модели и этого цвета
     *
     * @return static[]
     */
    public function getSizes()
    {
        $query = Product::find()
            ->select(['size_id'])
            ->where([
                'model_id' => $this->model_id,
                'color_id' => $this->color_id
            ])
            ->groupBy('size_id');

        return Size::findAll($query);
    }


    public function getColors()
    {
        $query = self::find()
            ->select('color_id')
            ->where(['model_id' => $this->model_id, 'size_id' => $this->size_id])
            ->groupBy('color_id');

        return Color::findAll([ 'id' => $query ]);
    }

    public function getType()
    {
        return $this->model->type;
    }

}