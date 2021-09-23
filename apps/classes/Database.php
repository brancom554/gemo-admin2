<?php 

class Database extends PDO
{
    private $driver,$db,$host,$user, $password = "";

    public function __construct()
    {
        
        //parent::__construct($dns, $settings['DATABASE']['DbUser'], $settings['DATABASE']['DbPass']);
    }

    public function connectDb($file = _APPS_PATH.'/config/application.ini')
    {
        if (!$settings = parse_ini_file($file, TRUE)) throw new exception('Unable to open ' . $file . '.');
        $this->driver = $settings['DATABASE']['DbDriver'];
        $this->host = $settings['DATABASE']['DbHost'];
        $this->db = $settings['DATABASE']['DbName'];
        $this->user = $settings['DATABASE']['DbUser'];
        $this->password = $settings['DATABASE']['DbPass'];
        try {
            return new PDO("$this->driver:host=$this->host;dbname=$this->db",$this->user,$this->password);
            // set the PDO error mode to exception
            $dbs->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
          } catch(PDOException $e) {
            //echo "Connection failed: " . $e->getMessage();
          }
    }

    public function InsertDb(String $sql,array $data)
    {
        $db = $this->connectDb();
        $db->beginTransaction();
        $query = $db->prepare($sql);
        if ($query->execute($data)) {
            $db->commit();
            return true;
        }else {
            return $query->errorInfo();
        }
    }

    public function InsertDb_Id(String $sql,array $data)
    {
        $db = $this->connectDb();
        $db->beginTransaction();
        $query = $db->prepare($sql);
        if ($query->execute($data)) {
            $last_id = (int)$db->lastInsertId();
            $db->commit();
            return $last_id;
        }else {
            return $query->errorInfo();
        }
    }

    public function DisplayDataDb(String $sql):array
    {
        $db = $this->connectDb();
        $db->beginTransaction();
        $query = $db->query($sql);
        if ($query) {
            $db->commit();
            return $query->fetchAll();
        }else {
            //var_dump($sql);
            return $db->errorInfo();
        }
    }

    public function DisplaysDataDb(String $sql):array
    {
        $db = $this->connectDb();
        $db->beginTransaction();
        $query = $db->query($sql);
        if ($query) {
            $db->commit();
            return $query->fetch();
        }else {
            //var_dump($sql);
            return $db->errorInfo();
        }
    }
}
?>