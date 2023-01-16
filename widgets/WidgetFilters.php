<?php
namespace app\widgets;

use app\models\Brand;
use app\models\Size;
use app\models\Color;
use app\models\Filters;
use app\models\PropertyType;
use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use Yii;

class WidgetFilters extends Widget
{
    public $filters;

    public $params;
    public $avalible;

    /** @var Filters  */
    public $current;

    public $content;

    public function run()
    {

        $this->content = [];
        foreach($this->filters as $filter) {
            $this->current = $filter;
            $this->renderFilterContent();
        }

        foreach($this->filters as $filter) {
            $parent = $filter->parent_filter;
            if($parent && isset($this->content[$parent]))
            {
                $this->content[$parent] .= $this->renderChildFilter($filter, $this->content[$filter->id]);
                unset($this->content[$filter->id]);
                //  теряется связь с родителем у поддочерних фильтров !
            }
        }

        $filters = ArrayHelper::index($this->filters, 'id');


        echo '<form id="filters-form" action="'.Url::to(['/catalog/filters']).'" method="post">
        <input type="hidden" name="'.Yii::$app->request->csrfParam.'" value="'.Yii::$app->request->csrfToken.'" />';
		echo '<input type="hidden" name="link" value="'.$_SERVER['REQUEST_URI'].'" />';
        echo '<ul>';
        foreach($this->content as $id => $html)
        {
            if($html == '') continue;
            echo '<li><ul>';
            echo $this->renderFilter($filters[$id], $html);
            echo '</ul></li>';
        }
        echo '</ul>';

        echo '</form>';


    }


    public function renderProperty($property)
    {
        $type = $this->current->type;

        if($type == Filters::TYPE_RANGE){
            if($property->format == PropertyType::IS_NUMBER)
            {
                return $this->renderRange($property);
            }
        }
		elseif($type == Filters::TYPE_EXIST){
            return $this->renderExist($property);
        }
		elseif($type == Filters::TYPE_UNION){
            if(in_array($property->format,[
            	PropertyType::IS_UNION,
	            PropertyType::IS_COLOR,
	            PropertyType::IS_SIZE,
	            PropertyType::IS_BRAND]
            ))  return $this->renderUnion($property);
        }
		return '';
    }

    public function renderFilterContent()
    {
		$content = '';
	    $properties = $this->current->properties;
        foreach($properties as $property)
        {
            if(!isset($this->avalible[$property->id]))
                continue;

            $content .= '<li class="filter_body">';
            $content .= $this->renderProperty($property);
            $content .= '</li>';
        }

        $this->content[$this->current->id] = $content;
    }

    public function renderRange($property)
    {
        $filter = $this->current->id;
        $params = $this->params;

        $from = isset($params[$filter][$property->id][0]) ? $params[$filter][$property->id][0] : '';
        $to   = isset($params[$filter][$property->id][1]) ? $params[$filter][$property->id][1] : '';

        return '<span>'.$property->name.'</span> 
        от <input name="filters['.$filter.']['.$property->id.'][0]" 
        type="text" value="'.$from.'">
         - до <input name="filters['.$filter.']['.$property->id.'][1]"
          type="text" value="'.$to.'">';
    }

    public function renderExist($property)
    {
        $params = $this->params;
        $filter_id = $this->current->id;

        $selected = isset($params[$filter_id][$property->id]) ? 'checked' : '';
        return '<span>'.$property->name.'</span>
        <input name="filters['.$filter_id.']['.$property->id.']" type="checkbox" '.$selected.'>';
    }

    public function renderUnion($property)
    {
		
        $options = $property->values;
		ArrayHelper::multisort($options, ['name'], [SORT_ASC]);
		
        if(empty($options)) return '';
		$active = isset($this->params[$this->current->id][$property->id]) ? 'active' : '' ;
        $content = '<span class="'.$active.'">'.$property->name.'<span class="doublename">: <span class="doublename_text"></span><span class="else"> и еще <span></span> </span></span><img class="imgarb" src="/web/img/arb.png"/><img class="imgcross" src="/web/img/cross.png"/></span>';
        $content .= '<div>';
        foreach($options as $option)
        {
            $selected = isset($this->params[$this->current->id][$property->id]) && in_array($option->id, $this->params[$this->current->id][$property->id]) ? 'checked' : '' ;
            $content .= '<p><label><input name="filters['.$this->current->id.']['.$property->id.'][]" value="'.$option->id.'" type="checkbox" '.$selected.'><span>'.$option->name.'</span></label></p>';
        }
		$content .= '</div>';
        return $content;
    }


    public function renderFilter($filter, $content)
    {

        
        $body = '<div class="filter-content clearfix"><span>Фильтры:</span><div class="filter_last_body">'.$content.'<div class="buttonsloaas"><button type="submit" class="button">Применить</button><button class="button button2">Сбросить</button></div></div></div>';

        $html = $body;

        return $html;
    }


    public function renderChildFilter($filter, $content)
    {
        if($content == '') return '';
        return '<div class="child-filter"><div><h4>'.$filter->name.'</h4></div><div>'.$content.'</div></div>';
    }




    public function renderColorFilter()
    {
        $colors = Color::find()->all();

        $content = '';

        foreach($colors as $color)
        {
            $selected = isset($params[$this->current->id][$color->id]) ? 'checked' : '';
            $content.= '<span>'.$color->name.'</span><input name="filters['.$this->current->id.']['.$color->id.']" type="checkbox" '.$selected.'>';
        }

        return $content;
    }
	public function renderSizeFilter()
    {
        $sizes = Size::find()->all();

		$options = $sizes;
		ArrayHelper::multisort($options, ['name'], [SORT_ASC]);
		
        if(empty($options)) return '';
		$active = isset($this->params[$this->current->id][$property->id]) ? 'active' : '' ;
        $content = '<span class="'.$active.'">'.$property->name.'<span class="doublename">: <span class="doublename_text"></span><span class="else"> и еще <span></span> </span></span><img class="imgarb" src="/web/img/arb.png"/><img class="imgcross" src="/web/img/cross.png"/></span>';
        $content .= '<div>';
        foreach($options as $option)
        {
            $selected = isset($this->params[$this->current->id][$property->id]) && in_array($option->id, $this->params[$this->current->id][$property->id]) ? 'checked' : '' ;
            $content .= '<p><label><input name="filters['.$this->current->id.']['.$property->id.'][]" value="'.$option->id.'" type="checkbox" '.$selected.'><span>'.$option->name.'</span></label></p>';
        }
		$content .= '</div>';
        return $content;
		

    }
    public function renderBrandFilter()
    {
        $brands = Brand::find()->all();

        $content = '';

        foreach($brands as $brand)
        {
            $selected = isset($params[$this->current->id][$brand->id]) ? 'checked' : '';
            $content.= '<span>'.$brand->name.'</span><input name="filters['.$this->current->id.']['.$brand->id.']" type="checkbox" '.$selected.'>';
        }

        return $content;
    }
}