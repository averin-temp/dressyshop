<?php

namespace app\modules\orders\models;

use yii\base\Model;

class AdminComment extends Model
{
    public $comment;

    function rules()
    {
        return [
            [ 'comment', 'required', 'message' => 'нельзя сохранить пустой комментарий']
        ];
    }



}