<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15.11.2018
 * Time: 10:39
 */

require 'classes/classes.php';
define('PATTERN_FOR_MAC', '/(\w{2}-){5}\w{2}$/');
/**
 * @param $log_text
 */

    $mac = $_POST['mac_out'] ?? '20-41-53-59-4e-ff';//die('нихуя не пришло');
    database::check_mac($mac, PATTERN_FOR_MAC); //проверка мак адреса регулярным вырожением, если не прошла валидация пишется в лог файл и дает команду ожидать

    $connect = new database();


    $sql = "SELECT * FROM logger.otm WHERE control_criterion = '$mac'";
    $object = $connect->execute($sql); // or (write_log('incoming mac-address is not controlled: ' . $mac) or die('4c4'));

    $object = $object[0];
    if ($object) {
        $sql = "UPDATE logger.otm SET active = NOW() WHERE control_criterion = '$mac' ";
        $connect->query($sql);
        switch ($object['work_mode']) {
            case 11:
                die('11'); //удалить
            case 22:
            case 33:
                break;
            case 44:
                die('44'); //ожидать
        }
    } else {
        database::write_log('incoming mac-address is not controlled: ' . $mac);
        die('44');
    }

    $newObject = new object_control($object);
    echo $newObject->object_check();
    // создаем новую сессию и устанавливает куки
    $new_session = new authentication();
    //
    if (!isset($_SESSION['mac_validate'])) {
        $_SESSION['mac_validate'] = $newObject->control_criterion;
        $_SESSION['object_name'] = $newObject->object_name;
        $_SESSION['id_otm'] = $newObject->id;
    }

//    var_dump($_SESSION);
//session_destroy();