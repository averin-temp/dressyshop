<?php

use yii\helpers\Url;

?>


    <ul>
		<li><span>Сортировать:</span> </li>
        <?php foreach($fields as $field => $label): $params['sort'] = $field; ?>
            <li <?php if($sort == $field): ?>class="active"<?php endif; ?>><a href="<?= '?'.http_build_query($params) ?>"> <?= $label ?></a></li>
        <?php endforeach; ?>
    </ul>
