<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\PropertyType;
use app\classes\PropertyHelper;
use yii\helpers\Html;

$this->title = "Характеристики товара";

?>

<?= $this->render('_menu', ['model' => $model]) ?>

<div id="properies" class="form-horizontal">

    <form action="<?= Url::to(['save']) ?>" method="post">
        <?= Html::hiddenInput('model', $model->id) ?>

        <?php
        echo '<div class="row newchars" >';
        $content = [];

        // $properties[ type.id ] = [ value, value, .. ],
        // для IS_NUMBER и IS_TEXT значение одно
        foreach($property_types as $type)
        {
            $values = isset($properties[ $type->id ]) ? $properties[ $type->id ] : [];

            if($type->format == PropertyType::IS_UNION)
            {
				$arr = $type->values;
				ArrayHelper::multisort($arr, ['name'], [SORT_ASC]);
                $items = ArrayHelper::map($arr, 'id', 'name');
                $content[] = PropertyHelper::layoutUnion( $items, $values, $type->name, $type->id );
            }

            elseif($type->format == PropertyType::IS_NUMBER)
            {
                $value = isset($values[0]) ? $values[0] : '';
                $content[] = PropertyHelper::layoutNumber($value, $type->name, $type->id);
            }

            elseif($type->format == PropertyType::IS_TEXT)
            {
                $value = isset($values[0]) ? $values[0] : '';
                $content[] = PropertyHelper::layoutText($value, $type->name, $type->id);
            }
        }


        for($k = 0, $i = 0; $i < count($content); $i+=$k )
        {

            for( $k = 0 ; $k < 2 ; $k++)
            {
                $data = isset($content[$i+$k]) ? $content[$i+$k] : '' ;
                echo '<div class="col-md-12">'.$data.'</div>';
            }

        }
        echo '</div>';
        ?>

        <?= Html::submitButton('Сохранить изменения',['class' => 'btn btn-success']) ?>

    </form>
</div>