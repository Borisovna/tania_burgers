<?php
require_once 'login.php';
$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
if ($connection->connect_errno) {
    printf ("Не удалось подключиться: %s\n", $connection->connect_error);
    exit();
}
//-проверка есть ли юзер с таким email
if (!empty($_POST['name']) and !empty($_POST['email'])) {
    $result_mail = $connection->query ('SELECT email FROM info_user');
    $data_mail = $result_mail->fetch_all ();
    foreach ($data_mail as $key => $value) {
        if ($data_mail[$key][0] == $_POST['email']) {
            $flag = true;
            break;
        } else {
            $flag = false;
        }
    }
} else {
    echo 'данные переданы не были!';
    exit();
}
//-запрос на запись в базу нового покупателя
$query_insert_userinfo = "INSERT INTO info_user (name,email,phone,street,home,appt,part) VALUES ('$_POST[name]','$_POST[email]','$_POST[phone]','$_POST[street]','$_POST[home]','$_POST[appt]','$_POST[part]')";
//-запрос на получение id покупателя
$query_id_userinfo = "SELECT id_user FROM info_user WHERE email='$_POST[email]'";

// если нет, такого покупателя мы его записываем в таблицу info_user, и потом записываем в базу даные о заказе, если есть - только данные о заказе.
if (!$flag == true) {
    $rezult_insert_userinfo = $connection->query ($query_insert_userinfo);
    $rezult_id_userinfo = $connection->query ($query_id_userinfo);
    $data1 = $rezult_id_userinfo->fetch_row ();
    //-запрос на запись нового заказа в базу
    $query_insert_order = "INSERT INTO `order` (id_user,comment) VALUES ('$data1[0]','$_POST[comment]')";
    $rezult_insert_order = $connection->query ($query_insert_order);
    $count_order = 1;
} else {
    echo '<br> такой покупатель у нас был!';
    $rezult_id_userinfo = $connection->query ($query_id_userinfo);
    $data1 = $rezult_id_userinfo->fetch_row ();
    //-запрос на запись нового заказа в базу
    $query_insert_order = "INSERT INTO `order` (id_user,comment) VALUES ('$data1[0]','$_POST[comment]')";
    $rezult_insert_order = $connection->query ($query_insert_order);
    //-запрос на получение все id покупателя в таблице заказов
    $query_allid_userinfo = "SELECT id_user FROM `order` WHERE id_user=$data1[0]";
    $rezult_allid_userinfo = $connection->query ($query_allid_userinfo);
    $count_id = $rezult_allid_userinfo->fetch_all ();
    $count_order = count ($count_id);
    
}
//получаем id_order последней записи в таблицу заказы, собераем массив данных для письма покупателю, записываем письмо в файл
$query_idorder = $connection->query ("SELECT id_order FROM `order`");
$arrey_idorder=$query_idorder->fetch_all ();
//echo '<pre>';
//print_r ($arrey_idorder);
$end_idorder = end ($arrey_idorder);
mysqli_close ($connection);
$arr_list['id_order'] = $end_idorder[0];
$arr_list['adres'] = "Улица $_POST[street], дом $_POST[home], квартира $_POST[appt], этаж $_POST[part]";
$arr_list['count_order'] = $count_order;
fopen ('list.txt', 'w');
if ($arr_list['count_order'] == 1) {
    $srt_list = "Заказ № $arr_list[id_order], <br> Ваш заказ будет доставлен по адресу : $arr_list[adres] /. <br> DarkBeefBurger за 500 рублей, 1 шт/.<br> Спасибо - это ваш первый заказ!";
} else {
    $srt_list = "Заказ № $arr_list[id_order], <br> Ваш заказ будет доставлен по адресу : $arr_list[adres] /. <br> DarkBeefBurger за 500 рублей, 1 шт/.<br> Спасибо! Это уже $arr_list[count_order] заказ!";
}
file_put_contents ('list.txt', $srt_list);
//header (location)