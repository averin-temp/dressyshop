<?php
namespace app\modules\products\models;


use yii\base\Model;
use app\classes\Utilities;
use app\models\Questions;
use app\models\Reviews;

class BatchAction extends Model
{
    public $changesort;
    public $change_enable;
    public $formula;
    public $action;
    public $keys;

    public function rules()
    {
        return [
            [ ['change_enable', 'changesort','formula', 'action', 'keys', 'double'] , 'safe' ]
        ];
    }

    public function formName()
    {
        return 'actionform';
    }

    public function Execute()
    {
        if(empty($this->keys))
            return false;

        $action = 'execute'.ucfirst($this->action);

        if(method_exists($this, $action))
            return $this->$action();

        else return null;
    }

    private function executeChangesort()
    {
        \app\models\Model::updateAll(['sort' => $this->changesort], ['id' => explode(',',$this->keys)]);
        return true;
    }

    private function executeChange_enable()
    {
        \app\models\Model::updateAll(['enable' => $this->change_enable], ['id' => explode(',',$this->keys)]);
        return true;
    }

    private function executeDelete()
    {
        \app\models\Model::deleteAll(['id' => explode(',',$this->keys)]);
        \app\models\Product::deleteAll(['model_id' => explode(',',$this->keys)]);

//        Utilities::removeImages($images);
        \app\models\Reviews::deleteAll(['model_id' => explode(',',$this->keys)]);
        \app\models\Questions::deleteAll(['model_id' => explode(',',$this->keys)]);

        return true;
    }

    private function executeDouble(){
        die('Функция в разработке');
    }

    private function executeFormula()
    {
        $pattern = '/^\s*([+-])\s*(\d+)\s*(%?)\s*$/';

        $formula = $this->formula;

        $results = [];

        if(!preg_match($pattern, $formula, $results))
            return false;

        $percent = empty($results[3])  ? false : true;
        $sign = $results[1];
        $number = $results[2];

        $models = \app\models\Model::findAll(['id' => explode(',', $this->keys)]);

        foreach($models as $model)
        {
            $price = $model->purchase_price;

            if($percent) {
                $number *= 0.01 * $price;
            }


            switch($sign){
                case "+":
                    $price += $number;
                    break;
                case "-":
                    $price -= $number;
                    break;
            }

            $model->purchasePrice = $price;
            $model->save();
        }

        return true;

    }



}