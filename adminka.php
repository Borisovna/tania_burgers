<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<?php
require_once 'login.php';
$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
if ($connection->connect_errno) {
    printf ("Не удалось подключиться: %s\n", $connection->connect_error);
    exit();
}

$query_select_infouser="SELECT info_user.id_user,info_user.name,info_user.email, info_user.phone, info_user.street, info_user.home FROM info_user";
$rezult_select_infouser = $connection->query ($query_select_infouser);
$arr_infouser = $rezult_select_infouser->fetch_all  ();

echo "<table border=\"2\" cellpadding=\"5\" align=\"center\">
   <caption><strong style=\"color:darkslateblue; font-size: 1.5em\">Таблица пользователей</strong></caption>;
   <tr>
    <th>Номер покупателя</th>
    <th>Имя покупателя</th>
    <th>email покупателя</th>
    <th>Телефон покупателя</th>
    <th>Улица покупателя</th>
    <th>Дом покупателя</th>
   </tr>";
for($i=0; $i<count ($arr_infouser);$i++){
    echo "<tr>";
    
    for ($j=0;$j<count ($arr_infouser[$i]);$j++){
        
        echo "<td>";
        print_r ($arr_infouser[$i][$j]);
        echo "</td>";
    }
    echo "</tr>";
}
echo "</table>";

$query_select_order="SELECT order.id_order,info_user.name, `order`.comment FROM `order` LEFT JOIN info_user on `order`.id_user=info_user.id_user";
$rezult_select_order = $connection->query ($query_select_order);
$arr_order = $rezult_select_order->fetch_all ();

echo "<table border=\"2\" cellpadding=\"5\" align=\"center\">
   <caption><strong style=\"color: purple; font-size: 2em\">Таблица заказов </strong></caption>
   <tr>
    <th>Номер заказа</th>
    <th>Имя покупателя</th>
    <th>Содержание заказа</th>
   </tr>";
for($i=0; $i<count ($arr_order);$i++){
    echo "<tr>";
        for ($j=0;$j<count ($arr_order[$i]);$j++){
    
        echo "<td>";
        print_r ($arr_order[$i][$j]);
        echo "</td>";
    }
    echo "</tr>";
}
echo "</table>";

?>
</body>
</html>