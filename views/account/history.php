<?php
if (!$orders) {
    ?>
    Заказы товары отсутствуют.<br>Перейдите в <a href='/latest'>каталог</a>.
    <?php
}
else{
?>

<div class="historyteaa">
    <table width="100%">
        <thead>
        <tr>
            <th>№</th>
            <th>Дата</th>
            <th>Стоимость</th>
            <th>Способ доставки</th>
            <th>Способ оплаты</th>
            <th>Статус заказа</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order->id+1000 ?></td>
                <td><?= date_create($order->created)->Format('d-m-Y'); ?></td>
                <td><?= $order->cost ?> руб.</td>
                <td><?= \app\models\Delivery::findOne($order->delivery_id)->caption ?></td>
                <td><?= \app\models\Pay::findOne($order->pay_method)->caption ?></td>
                <td><?= \app\models\Status::findOne($order->status_id)->name ?></td>
            </tr>

        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php }?>