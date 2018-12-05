<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15.11.2018
 * Time: 10:38
 */

require 'classes/classes.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

ini_set('display_errors',1);
error_reporting(E_ALL);

$check_session = new authentication();
$check_session->validate_session();

$id_otm = $_SESSION['id_otm'];
$object_name = $_SESSION['object_name'];
$fileType = $_SERVER['HTTP_ACCEPT'] ?? 'und_type';

$file = $_FILES['file'] ?? die('Файл не получен');
$data = new data($file, $fileType);

if (!$data->move_file())
{
    $log = 'Data from '.$object_name.' can not be move. Error: write error!';
    database::write_log($log);
    die('Data from '.$object_name.' can not be move. Error: write error!');
}

$connect = new database();
$sql = "INSERT INTO logger.data (id_otm, file, type) VALUES ('$id_otm', '$data->fileName', '$data->fileType')";
$connect->query($sql);
echo 'OK, data is write';
