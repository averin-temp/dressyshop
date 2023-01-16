<?php
use app\assets\JcropAsset;
JcropAsset::register($this);
?>
<div id="modal_box" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="row">
                <div class="col-xs-10">
                    <div class="edit-box">
                        <img src="http://cdn.wallpapersafari.com/2/47/pEmBT6.jpg" alt="">
                    </div>
                </div>
                <div class="col-xs-2">
                    <div class="image-options">
                        <div class="input-group">

                            <div class="checkbox">
                                <label>
                                    <input name="primary" type="checkbox" value="1">
                                    Титульная
                                </label>
                            </div>

                            <button type="button" class="btn btn-primary save-button">Сохранить</button>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
