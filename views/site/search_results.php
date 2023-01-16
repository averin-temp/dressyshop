<?php if(!empty($data)): ?>
<ul>
<?php foreach($data as $item): ?>
    <li><?= isset($item['info']) ? "<p>".$item['info']."</p>" : '' ?><a href="<?= $item['target']->link ?>"><?= $item['data'] ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>