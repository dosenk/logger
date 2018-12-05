<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14.11.2018
 * Time: 9:56
 */

class database
{
    private $link;
    private $_sql;
    private $_result;

    function __construct()
    {
        $config = require 'config/config.php';
        $dsn = 'mysql:host='.$config['host'].';dbname='.$config['db_name'].';charset='.$config['charset'];
        $this -> link = new PDO($dsn, $config['username'], $config['password']);
    }

    public function query(string $sql)
    {
        $sth = $this->link->prepare($sql);
        $sth -> execute();
    }

    public function execute(string $sql)
    {
        $this->_sql = $sql;
        $sth = $this->link->prepare($sql);
        $sth->execute();
        while ($array = $sth->fetch(PDO::FETCH_ASSOC)) {
             $this->_result[] = $array;// = $sth->fetch(PDO::FETCH_ASSOC);
            }
        return $this->_result;
    }

    public static function write_log(string $log_text)
    {
        $current_date = date('Y-m-d H:i:s');
        $file = '/var/log/logger/error_log';
        $fOpen = fopen($file, 'a');
        fwrite($fOpen, $current_date . '; server: ' . $_SERVER['REMOTE_ADDR'] . ' - ' . $log_text . "\r\n");
        fclose($fOpen);
    }

    public static function check_mac($mac, $pattern)
    {
    if (preg_match($pattern, $mac)) return;
    $log_text = 'incoming mac-address failed validation: ' . $mac; //что писать
    self::write_log($log_text);
    die('44'); //ожидать
    }

//    function __destruct()
//    {
//        $this->link = null;
//    }
}

class object_control
{
    public $id;
    public $object_name;
    private $_otm;
    public $control_criterion;
    public $start_date;
    public $end_date;
    public $work_mode;

    /**
     * Create object with incoming options from DB table
     * @param array $object
     */
    public function __construct(array $object)
    {
        $this->id = $object['id'] ?? null;
        $this->object_name = $object['object'] ?? null;
        $this->_otm = $object['otm'] ?? null;
        $this->control_criterion = $object['control_criterion'] ?? null;
        $this->start_date = $object['start_date'] ?? null;
        $this->end_date = $object['end_date'] ?? null;
        $this->work_mode = $object['work_mode'] ?? null;
    }

    /**
     * Проверяем, соит ли обьект на контроле.
     * Если стоит, возвращает режим работы
     */
    public function object_check():string
    {
        $current_date = strtotime(date('Y-m-d'));
        if (strtotime($this->start_date) > $current_date) {
            die('44'); //ожидать
        } elseif ($current_date <= strtotime($this->end_date)) {
            return $this->work_mode; //режим работы (22 или 33)
        } else {
            $connect = new database();
            $slq = "UPDATE logger.otm SET work_mode = 44 WHERE control_criterion = '$this->control_criterion' ";
            $connect->query($slq);
            database::write_log('The terms of the sanctions are over. The object is transferred to the standby mode: 44');
            die('44'); //ожидать
        }
    }

    public function export_data($export_start_date, $export_end_date)
    {
        $object_upper_word = mb_strtoupper(substr($this->object_name, 0, strpos($this->object_name, '-')));
        $connect = new database();
//        echo $export_date;
//        echo $this->id;
        $sql = "SELECT file, type FROM logger.data WHERE id_otm = '$this->id' AND timestamp >= '$export_start_date' AND timestamp <= '$export_end_date'";
        $data = $connect->execute($sql) or die('Данных нет');
//        var_dump($data);
        $path = "AUTO_EXP/$object_upper_word/$this->object_name/$this->control_criterion/$export_start_date";
        for ($i = 0; $i <= (count($data)-1); $i++) {
            $dataType = $data[$i]['type'] ?? 'undefined type DATA';
            if (!file_exists($path. '/' . $dataType)) mkdir($path . '/' . $dataType, 0777, true);
            $pathOld = '/var/www/html/logger/files/'. $data[$i]['file'];
            copy($pathOld,$path. '/' . $dataType.'/'.$data[$i]['file']);
        }
    }
}

class authentication {

    private $lifetime_session;
    public $session_id;


    const SECOND_IN_DAY = 86400;
    const SECOND_IN_HOUR = 3600;
    const SECOND_IN_MIN = 60;
    const NAME_COOKIE = 'MAC';

    function __construct()
    {
        $this->get_lifetime();
        session_name(self::NAME_COOKIE);
        session_set_cookie_params($this->lifetime_session, '/');
        ini_set('session.gc_maxlifetime', $this->lifetime_session);
        session_start();
    }

    private function get_lifetime()
    {
        $date = new DateTime();
        $current_time = $date->format('H:i:s');
        $current_hour = explode(':', $current_time)[0];
        $current_min = explode(':', $current_time)[1];
        $current_sec = explode(':', $current_time)[2];
        $this->lifetime_session = self::SECOND_IN_DAY - $current_hour * self::SECOND_IN_HOUR -
            $current_min * self::SECOND_IN_MIN - $current_sec;
    }

    public function validate_session()
    {
        if (!isset($_SESSION['mac_validate']))
        {
            die('check.php');
        }

    }
}

class data
{
    public $path;
    public $fileType;
    public $fileName;
    private $_fileError;
    private $_fileTmpName;

    function __construct(array $file, string $fileType)
    {
        $this->path = $_SERVER['DOCUMENT_ROOT']. '/logger/files/'.$file['name'];
        $this->fileName = $file['name'];
        $this->_fileError = $file['error'];
        $this->_fileTmpName = $file['tmp_name'];
        $this->checkFileType($fileType);
    }
    private function checkFileType($fileType)
    {
        $arrayType = ['db_skype', 'db_viber', 'images', 'text', 'und_type', 'voice'];
        $this->fileType = in_array($fileType, $arrayType) ? $fileType : 'und_type';
    }

    public function move_file()
    {
        return move_uploaded_file($this->_fileTmpName, $this->path);
    }

}


