<?php require_once __DIR__ . "/vendor/autoload.php";

//$loader = new Twig_Loader_Filesystem('templates');
//$twig = new Twig_Environment($loader);
//echo $twig->render('ind.php');
require_once 'login.php';
$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
if ($connection->connect_errno) {
    printf("Не удалось подключиться: %s\n", $connection->connect_error);
    exit();
}
//вывод таблицы зарегистрированых пользователей
try {
    // формируем SELECT запрос
    // в результате каждая строка таблицы будет объектом
    $sql = "SELECT info_user.id_user,info_user.name,info_user.email, info_user.phone, info_user.street, info_user.home FROM info_user";
    $sth = $connection->query($sql);
    $row = $sth->fetch_all();
    // формируем ассоциативный массив для шаблона
    for ($i = 0; $i < count($row); $i++) {
        for ($j = 0; $j < count($row[$i]); $j++) {
            switch ($j) {
                case '0':
                    $data[$i]['num'] = $row[$i][$j];
                    break;
                case '1':
                    $data[$i]['name'] = $row[$i][$j];
                    break;
                case '2':
                    $data[$i]['email'] = $row[$i][$j];
                    break;
                case '3':
                    $data[$i]['phone'] = $row[$i][$j];
                    break;
                case '4':
                    $data[$i]['street'] = $row[$i][$j];
                    break;
                case '5':
                    $data[$i]['home'] = $row[$i][$j];
                    break;
            }
        }
    }
//    echo '<pre>';
//    print_r($data);
    $loader1 = new Twig_Loader_Filesystem('templates');
    $twig1 = new Twig_Environment($loader1);
    echo $twig1->render('adminka.html',[
        'data' => $data
    ]);
} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}

//вывод таблицы заказов
try {
    // формируем SELECT запрос
    // в результате каждая строка таблицы будет объектом
    $sql1 = "SELECT order.id_order,info_user.name, `order`.comment FROM `order` LEFT JOIN info_user on `order`.id_user=info_user.id_user";
    $sth1 = $connection->query($sql1);
    $row1 = $sth1->fetch_all();
    
    // формируем ассоциативный массив для шаблона
    for ($i = 0; $i < count($row1); $i++) {
        for ($j = 0; $j < count($row1[$i]); $j++) {
            switch ($j) {
                case '0':
                    $orders[$i]['num'] = $row1[$i][$j];
                    break;
                case '1':
                    $orders[$i]['name'] = $row1[$i][$j];
                    break;
                case '2':
                    $orders[$i]['order'] = $row1[$i][$j];
                    break;
            }
        }
    }
//    echo '<pre>';
//    print_r($orders);
    $loader1 = new Twig_Loader_Filesystem('templates');
    $twig1 = new Twig_Environment($loader1);
    echo $twig1->render('adminka.html',[
        'orders' => $orders
    ]);
} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}
mysqli_close ( $connection);