<?php
namespace app\classes;

use app\models\Property;
use app\models\PropertyType;
use yii\base\Object;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class PropertyHelper extends Object
{
    public static function layoutSelect($items = [], $values = [], $label = '', $type_id = '')
    {

        $layout = '<div class="form-group">';
        $layout .= '<div class="remove-property">удалить</div>';
        $layout .= '<label for="property-'.$type_id.'" class="col-sm-6 control-label">'.$label.'</label>';
        $layout .= '<select name="properties['.$type_id.'][]" id="property-'.$type_id.'" class="form-control" multiple >';
        foreach($items as $id => $val)
        {
            $selected = in_array($id, $values) ? 'selected' : '' ;
            $layout .= '<option value="'.$id.'"'.$selected.'>'.$val.'</option>';
        }
        $layout .= '</select></div>';
        return $layout;
    }

    /**
     * Выводит форму свойства типа UNION
     *
     * @param array $items Список всех PropertyValue для типа в виде [ property_value.id => property_value.name ]
     * @param array $values Список ID PropertyValue , которые выбраны
     * @param string $label Имя свойства
     * @param string $type_id ID свойства
     * @return string
     */
    public static function layoutUnion($items = [], $values = [], $label = '', $type_id = '')
    {
        $layout = '<div class="well"><p>'.$label.'</p>';
        foreach($items as $id => $val)
        {
            $checked = in_array($id, $values) ? 'checked' : '' ;
            $layout .= '<label><input type="checkbox" name="properties['.$type_id.'][]" '.$checked.' value='.$id.'> '.$val.'</label><br>';
        }
        $layout .= '</div>';
        return $layout;
    }

    /**
     * Выводит форму свойства типа TEXT
     *
     * @param string $value Значение
     * @param string $label Имя свойства
     * @param string $type_id ID типа свойства
     * @return string
     */
    public static function layoutText($value = '', $label = '', $type_id = '')
    {
        $layout = '<div class="well"><p>'.$label.'</p>';
        $layout .= '<textarea name="properties['.$type_id.']" id="property-'.$type_id.'" class="form-control">'.$value.'</textarea>';
        $layout .= '</div>';
        return $layout;
    }

    /**
     * Выводит форму свойства типа NUMBER
     *
     * @param string $value Значение
     * @param string $label Имя свойства
     * @param string $type_id ID типа свойства
     * @return string
     */
    public static function layoutNumber($value = '', $label = '', $type_id = '')
    {
        $layout = '<div class="well"><p>'.$label.'</p>';
        $layout .= '<input type="number" name="properties['.$type_id.']" id="property-'.$type_id.'" value="'.$value.'" class="form-control">';
        $layout .= '</div>';
        return $layout;
    }

    /**
     * @param Property $property
     * @return string
     */
    public static function field($property)
    {
        $value = $property->value;
        /** @var PropertyType $type */
        $type = $property->type;
        $type_id = $type->id;
        $format = $type->format;
        $proplabel = $type->name;

        if($format == PropertyType::IS_NUMBER){
            return static::layoutNumber($value,$proplabel, $type_id);
        }
        elseif($format == PropertyType::IS_TEXT) {
            return static::layoutText($value,$proplabel, $type_id);
        }
    }


    public static function union($properties, $type)
    {

        $values = [];
        foreach($properties as $property)
        {
            $values[$property->id] = $property->value;
        }

        $proplabel = $type->name;

        $temp = $type->values;
        $items = ArrayHelper::map($temp, 'id', 'name');

        return static::layoutUnion($items, $values, $proplabel, $type->id);
    }

}