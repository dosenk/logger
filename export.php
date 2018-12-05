<?php
require 'classes/classes.php';

$connect = new database();
$date = new DateTime();
$export_end_date = $_POST['end_date'] ?? $date->format('Y-m-d');
$export_start_date = $_POST['start_date'] ?? $date->sub(DateInterval::createFromDateString('1 day'))->format('Y-m-d');
//echo $export_start_date." - ". $export_end_date;
$sql = "SELECT id, object, control_criterion, active FROM logger.otm WHERE '$export_start_date' <= end_date";
$result = $connect->execute($sql);
for ($i=0; $i <= (count($result)-1); $i++)
{
    $object[$i] = new object_control($result[0]);
    $object[$i]->export_data($export_start_date, $export_end_date);
//    echo $i."<br>";
}



