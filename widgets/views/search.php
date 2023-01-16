<?php

use yii\web\View;

$search = isset($_GET['search']) ? $_GET['search'] : false ;
					
?>

<div class="header_middle_search">

    <form action="/search/index" class="search-form" method="get">
        <input AUTOCOMPLETE="off" type="search" name="search" required placeholder="Поиск по сайту" value="<?php if($search){echo $search;}?>">
        <input type="submit" value="" class="button">
    </form>

    <div class="search-list" style="
    width: 100%;
    padding: 10px;
    background-color: white;
    border-right: 1px solid rgb(212, 212, 212);
    border-left: 1px solid rgb(212, 212, 212);
    border-image: initial;
    border-top: none;
    z-index: 2147483647;
    border-bottom: 3px ridge rgb(230, 76, 101);
    box-shadow: black 0px 10px 22px -7px;
display:none;"
><ul></ul></div>
</div>

<?php

$script = <<< JS

// Устанавливает обработчики формам поиска ( Search::widget() )
// При изменении значения поля [type=search] отправляется ajax запрос со значением поля.
// Возвращаемое значение - готовый HTML для .search-list, в нем отображаются результаты поиска
$('.header_middle_search').find('[type=search]').on('keyup', function(e){
    var that = $(this);
    var search = that.val();
    search.trim();
    if(search.length < 2) {
		$('.header_middle_search')
			.find('.search-list')
            .hide(0)
            .find('ul')
            .html(''); 
			return;
	}
   
 
    drassy_callback('search', {search: search});
    $.get( '/search/ajax', { string: search  }, function(data){
      drassy_callback('search_result', data);
        if(data.error === true)
            alert(data.message);
        else if(data.content.length){
            that.closest('.header_middle_search')
            .find('.search-list')
            .show(0)
            .find('ul')
            .html(data.content);
        } else {
            that.closest('.header_middle_search')
            .find('.search-list')
            .hide(0)
            .find('ul')
            .html('');    
        }
    });
});
// При потере фокуса у [type=search] , прячем .search-list
$(document).click(function(event) {
    if ($(event.target).closest(".header_middle_search").length) return;
    $('.header_middle_search').find('.search-list').hide(0)
    event.stopPropagation();
  });
  
// $('.header_middle_search').find('[type=search]').on('blur', function(){
    // $(this).closest('.header_middle_search').find('.search-list').hide(0);
// });

$('.search-form').submit(function(e){
    var text = $(this).find('[type="search"]').val();
    if(!text.length) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    }
});


JS;

$this->registerJS($script, View::POS_READY, 'search-handler');

