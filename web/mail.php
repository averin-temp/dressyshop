<?php
$message = '';
$recepient = trim($_GET["admin_email"]);
if($recepient == ''){
    $recepient = 'ssdim4ik@mail.ru';
    $message .= "Админ не выставил емейл, измените настройки сайта. \n\n";
}


if($_GET["formtype"] == 'sotrud'){
    $pagetitle = "DressyShop - Сотрудничество";
    $typrepred = trim($_GET["typepred"]);
    $name = trim($_GET["name"]);
    $phone = trim($_GET["phone"]);
    $email = trim($_GET["email"]);
    $text = trim($_GET["text"]);
    $message .= "Тип предложения: $typrepred \nИмя: $name \nКонтактный телефон: $phone \nE-mail: $email \nПредложение: $text \n";
}


else if($_GET["formtype"] == 'return_call'){
    $pagetitle = "DressyShop - обратный звонок";
    $name = trim($_GET["name"]);
    $phone = trim($_GET["phone"]);
    if($name == ''){
        $name = 'Не указано.';
    }
    $message .= "Имя: $name \nНомер телефона: $phone";
}
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/plain' . "\r\n";
$headers .= "From: Dressyshop <$recepient>\n";

@mail($recepient, $pagetitle, $message, $headers);















