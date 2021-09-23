<?php
/**
* http://www.phpclasses.org/browse/file/34541.html
* PDO Database class
*
* @author:      Evert Ulises German Soto
* @copyright:   wArLeY996 2011
* @version:   1.9
*
* v1.9: Se creo un metodo para retornar la descripcion del ultimo error ocurrido, y se creo tambien el metodo para prevenir SQL Injections.
* v1.8: Se optimizaron los metodos update, delete y getLatestId, y los metodos update y delete ya permiten condiciones vacias.
* v1.7: Se optimizo el metodo rowcount, se construye el query para hacer un count(*) en vez de recorrer los registros.
* v1.6: Se agrego el error handler de conexion, se modifico la estructura del constructor.
* v1.5: Se agregaron dos nuevas funciones: 1.- ShowTables and 2.- ShowDBS
* v1.4: Se controlan los mensajes de error.
* v1.3: Se reparo un error, la funcion "insert" solo funcionaba para MySQL y SQLSrv.
* v1.2: Se agrego la libreria "mssql", y se agrego tambien la funcion getLatestId(tabla, id)
* v1.1: Al ejecutar un insert, delete o update, regresa el total de renglones afectados.
* v1.0: Se creo la clase funcional.
*/



class sql extends PDO{

  private $database_types = array("sqlite2","sqlite3","sqlsrv","mssql","mysql","pg","ibm","dblib","odbc","oracle","ifmx","fbd");

  private $host;
  private $database;
  private $user;
  private $password;
  private $port;
  private $database_type;
  private $root_mdb;

  private $sql;
  private $con;
  private $err_msg = "";

  /**
  * Constructor of class - Initializes class and connects to the database
  * @param string $database_type the name of the database (sqlite2=SQLite2,sqlite3=SQLite3,sqlsrv=MS SQL,mssql=MS SQL,mysql=MySQL,pg=PostgreSQL,ibm=IBM,dblib=DBLIB,odbc=Microsoft Access,oracle=ORACLE,ifmx=Informix,fbd=Firebird)
  * @param string $host the host of the database
  * @param string $database the name of the database
  * @param string $user the name of the user for the database
  * @param string $password the passord of the user for the database
  *
  *  You can use this shortcuts for the database type:
  *
  *     sqlite2 -> SQLite2
  *     sqlite3 -> SQLite3
  *     sqlsrv  -> Microsoft SQL Server (Works under Windows, accept all SQL Server versions [max version 2008]) - TESTED
  *     mssql   -> Microsoft SQL Server (Works under Windows and Linux, but just work with SQL Server 2000) - TESTED
  *     mysql   -> MySQL - TESTED
  *     pg    -> PostgreSQL - TESTED
  *   ibm   -> IBM
  *   dblib -> DBLIB
  *   odbc  -> Microsoft Access
  *   oracle  -> ORACLE
  *   ifmx  -> Informix
  *   fbd   -> Firebird - TESTED
  */

  //Initialize class and connects to the database
  public function __construct($ini){
// print_r($ini);
  	$this->host = $ini->DbHost;
    $this->user = $ini->DbUser;
    $this->password = $ini->DbPass;
    $this->database = $ini->DbName;
    $this->dsn = "mysql:dbname=$this->database;host=$this->host";
    $this->port = $ini->DbPort;

  }

  //Initialize class and connects to the database
  public function Connect(){ //Cnxn
      try {
//  echo "<br> classdbPDO_new try called connect () <br> this ==> ";
// print_r($this);
          if(trim($this->port)){
            $this->con = new PDO("mysql:host=".$this->host.";port=".$this->port.";dbname=".$this->database, $this->user, $this->password);
          }else{
            $this->con = new PDO("mysql:host=".$this->host.";dbname=".$this->database, $this->user, $this->password);
          }
          /* Adding UTF8 charset to handle special char */
          $this->con->exec("SET NAMES 'utf8';");

         $this->con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
          $this->con->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

        $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        //$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        //$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

        //$this->con->setAttribute(PDO::SQLSRV_ATTR_DIRECT_QUERY => true);

        /*
        print_r($db->getAttribute(PDO::ATTR_CONNECTION_STATUS));
        print_r($db->getAttribute(PDO::ATTR_DRIVER_NAME));
        print_r($db->getAttribute(PDO::ATTR_SERVER_VERSION));
        print_r($db->getAttribute(PDO::ATTR_CLIENT_VERSION));
        print_r($db->getAttribute(PDO::ATTR_SERVER_INFO));
        */
//  echo "<br>before returning this con content<pre>";print_r($this->con);echo "<br>";
        return $this->con;
      }catch(PDOException $e) {
        $this->err_msg = "Error: ". $e->getMessage();
//         echo "<br>error returning this con content<pre> root password : ".$this->password." / ".$e->getMessage();;print_r($this->con);echo "<br>";
//         echo "<br><pre> ".$e->getMessage();;print_r($this->con);echo "<br>";

        return false;
      }
  }



  //Retrieve all drivers capables
  public function drivers(){
    print_r(PDO::getAvailableDrivers());
  }

  //Iterate over rows
  public function query($sql_statement){
    $this->err_msg = "";
    if($this->con!=null){
      try {
        $this->sql=$sql_statement;
        return $this->con->query($this->sql);
      } catch(PDOException $e) {
        $this->err_msg = "Error: ". $e->getMessage();
        return false;
      }
    }
    else{
      $this->err_msg = "Error: Connection to database lost.";
      return false;
    }
  }

  //Querys Anti SQL Injections
  public function query_secure($sql_statement, $params, $fetch_rows=false){
    $this->err_msg = "";
    if($this->con!=null){
      $obj = $this->con->prepare($sql_statement);
      for($i=0;$i<count($params);$i++){
        $params_split = explode("@",$params[$i]);
        if($params_split[2]=="INT")
          $obj->bindParam($params_split[0], $params_split[1], PDO::PARAM_INT);
        else
          $obj->bindParam($params_split[0], $params_split[1], PDO::PARAM_STR);
      }
      try {
        $obj->execute();
      }catch(PDOException $e){
        $this->err_msg = "Error: ". $e->getMessage();
        return false;
      }
      if($fetch_rows)
        return $obj->fetchAll();
      if(is_numeric($this->con->lastInsertId()))
        return $this->con->lastInsertId();
      return true;
    }
    else{
      $this->err_msg = "Error: Connection to database lost.";
      return false;
    }
  }

  //Fetch the first row
  public function query_first($sql_statement){
    $this->err_msg = "";
    if($this->con!=null){
      try {
        $sttmnt = $this->con->prepare($sql_statement);
        $sttmnt->execute();
        return $sttmnt->fetch();
      } catch(PDOException $e) {
        $this->err_msg = "Error: ". $e->getMessage();
        return false;
      }
    }
    else{
      $this->err_msg = "Error: Connection to database lost.";
      return false;
    }
  }

  //Select single table cell from first record
  public function query_single($sql_statement){
    $this->err_msg = "";
    if($this->con!=null){
      try {
        $sttmnt = $this->con->prepare($sql_statement);
        $sttmnt->execute();
        return $sttmnt->fetchColumn();
      } catch(PDOException $e) {
        $this->err_msg = "Error: ". $e->getMessage();
        return false;
      }
    }
    else{
      $this->err_msg = "Error: Connection to database lost.";
      return false;
    }
  }

  //Return total records from query as integer
  public function rowcount(){
    $this->err_msg = "";
    if($this->con!=null){
      try {
        $stmnt_tmp = $this->stmntCount($this->sql);
        if($stmnt_tmp!=false && $stmnt_tmp!=""){
          return $this->query_single($stmnt_tmp);
        }
        else{
          $this->err_msg = "Error: A few data required.";
          return -1;
        }
      } catch(PDOException $e) {
        $this->err_msg = "Error: ". $e->getMessage();
        return -1;
      }
    }
    else{
      $this->err_msg = "Error: Connection to database lost.";
      return false;
    }
  }

  //Return name columns as vector
  public function columns($table){
    $this->err_msg = "";
    $this->sql="Select * From $table";
    if($this->con!=null){
      try {
        $q = $this->con->query($this->sql);
        $column = array();
        foreach($q->fetch(PDO::FETCH_ASSOC) as $key=>$val){
           $column[] = $key;
        }
        return $column;
      } catch(PDOException $e) {
        $this->err_msg = "Error: ". $e->getMessage();
        return false;
      }
    }
    else{
      $this->err_msg = "Error: Connection to database lost.";
      return false;
    }
  }

  //Insert and get newly created id
  public function insertOLD($table, $data){
    $this->err_msg = "";
    if($this->con!=null){
      try {
        $texto = "Insert Into $table (";
        $texto_extra = ") Values (";
        $texto_close = ")";
        $data_column = explode(",", $data);
        print_r($data_column);
        for($x=0;$x<count($data_column);$x++){
          $data_content = explode("=", $data_column[$x]); //0=Field, 1=Value
          if($x==0){ $texto.= $data_content[0]; }
          else{ $texto.= "," . $data_content[0]; }
          if($x==0){ $texto_extra.= $data_content[1]; }
          else{ $texto_extra.= "," . $data_content[1]; }
        }
        $this->con->exec("$texto $texto_extra $texto_close");
         return $this->con->lastInsertId();
      } catch(PDOException $e) {
        $this->err_msg = "Error: ". $e->getMessage();
        return false;
      }
    }
    else{
      $this->err_msg = "Error: Connection to database lost.";
      return false;
    }
  }

  //Insert and get newly created id
  public function insertTransaction($table, $fields, $data){
    $this->err_msg = "";
    if($this->con!=null){
      try {
      	unset($field);
      	foreach($fields as $k=>$v){
      		if(isset($field)) $field.=",".$v; else $field=$v;
      	}
      	unset($values);      	unset($pValues);
        foreach($data as $k=>$v){
        	switch($v){
        		case "now()":
        			 if(isset($values)) $values.=",? "; else $values=" ? ";
        			 if(isset($pValues)) $pValues.="||".date('Y-m-d H:i:s'); else $pValues=date('Y-m-d H:i:s');
        	  break;
        		default:
        			if(isset($values)) $values.=",? "; else $values=" ? ";
        	   if(isset($pValues)) $pValues.="||$v"; else $pValues="$v";

        	  break;
        	}
        }
        $q = 'INSERT  $table ($field) VALUES ($values) ';
        // $q = $this->con->quote($q); // Automatically addslashes to the query
        $this->con->beginTransaction();
        $stmt =$this->con->prepare($q);
        $res = $stmt->execute(explode("||",$pValues));
        $lastId=$this->con->lastInsertId();

        $this->con->commit();
        return $lastId;
      } catch(PDOException $e) {
        $this->err_msg = "Error: ". $e->getMessage();
        return false;
      }
    }
    else{
      $this->err_msg = "Error: Connection to database lost.";
      return false;
    }
  }

public function insertTransactionSelect($table, $fields, $sql){
    $this->err_msg = "";
    if($this->con!=null){
      try {
        unset($field);
        foreach($fields as $k=>$v){
          if(isset($field)) $field.=",".$v; else $field=$v;
        }
        unset($values);       unset($pValues);

        $q = "INSERT  $table ($field) $sql ";

       // echo "query before exec : $q";
        $this->con->beginTransaction();
        $stmt =$this->con->prepare($q);
        $res = $stmt->execute(explode("||",$pValues));
        $lastId=$this->con->lastInsertId();

        $this->con->commit();
      } catch(PDOException $e) {
        $this->err_msg = "Error: ". $e->getMessage();
        return false;
      }
    }
    else{
      $this->err_msg = "Error: Connection to database lost.";
      return false;
    }
  }

  public function update($table, $fields, $conditions){
    $this->err_msg = "";
    $sql=$this->Connect();
    if($this->con!=null){
      try {
        unset($field);
        foreach($fields as $k=>$v){

          switch($v){
            case "now()":
            	   if(isset($field)) $field.=",$k=$v"; else $field="$k=$v";
            break;
            default:
              if(isset($field)) $field.=",$k='$v'"; else $field="$k='$v'";
            break;
          }
        }

        unset($values); unset($pValues);
        foreach($conditions as $k=>$v){
         if(isset($values)) $values.=" AND $k='$v' "; else $values="  $k='$v' ";
        }

        $q = "UPDATE  $table SET $field WHERE $values ";

        $this->con->beginTransaction();
        $stmt =$this->con->prepare($q);
        $res = $stmt->execute(explode("||",$pValues));
        $this->con->commit();
        return $res;
      } catch(PDOException $e) {
        $this->err_msg = "Error: ". $e->getMessage();

        return $e;
      }
    }
    else{
      $this->err_msg = "Error: Connection to database lost.";
      return false;
    }
  }


  //Update tables
  public function updateOLD($table, $data, $condition=""){
  	$sql=$this->Connect();
    $this->err_msg = "";
    if($this->con!=null){

      try {
        return (trim($condition)!="") ? $this->con->exec("update $table set $data where $condition") : $this->con->exec("update $table set $data");
      } catch(PDOException $e) {
        $this->err_msg = "Error: ". $e->getMessage();
        return false;
      }
    }
    else{
      $this->err_msg = "Error: Connection to database lost.";
      return false;
    }
  }

  //Delete records from tables
  public function delete($table, $condition=""){
    $this->err_msg = "";
    if($this->con!=null){
      try {
        return (trim($condition)!="") ? $this->con->exec("delete from $table where $condition") : $this->con->exec("delete from $table");
      } catch(PDOException $e){
        $this->err_msg = "Error: ". $e->getMessage();
        return false;
      }
    }
    else{
      $this->err_msg = "Error: Connection to database lost.";
      return false;
    }
  }

  //Execute Store Procedures
  public function execute($sp_query){
    $this->err_msg = "";
    if($this->con!=null){
      try {
        $this->con->exec("$sp_query");
        return true;
      } catch(PDOException $e) {
        $this->err_msg = "Error: ". $e->getMessage();
        return false;
      }
    }
    else{
      $this->err_msg = "Error: Connection to database lost.";
      return false;
    }
  }

  //Get latest specified id from specified table
  public function getLatestId($db_table, $table_field){
    $this->err_msg = "";
    $sql_statement = "";
    $dbtype = $this->database_type;

    if($dbtype=="mysql" || $dbtype=="sqlite2" || $dbtype=="sqlite3"){
      $sql_statement = "select $table_field from $db_table order by $table_field desc limit 1";
    }

    if($this->con!=null){
      try {
        return $this->query_single($sql_statement);
      } catch(PDOException $e) {
        $this->err_msg = "Error: ". $e->getMessage();
        return false;
      }
    }
    else{
      $this->err_msg = "Error: Connection to database lost.";
      return false;
    }
  }

  //Get all tables from specified database
  public function ShowTables($database){
    $this->err_msg = "";
    $complete = "";
    $sql_statement = "";
    $dbtype = $this->database_type;

    if($dbtype=="sqlsrv" || $dbtype=="mssql" || $dbtype=="ibm" || $dbtype=="dblib" || $dbtype=="odbc" || $dbtype=="sqlite2" || $dbtype=="sqlite3"){
      $sql_statement = "select name from sysobjects where xtype='U'";
    }
    if($dbtype=="oracle"){
      //If the query statement fail, try with uncomment the next line:
      //$sql_statement = "SELECT table_name FROM tabs";
      $sql_statement = "SELECT table_name FROM cat";
    }
    if($dbtype=="ifmx" || $dbtype=="fbd"){
      $sql_statement = "SELECT RDB$RELATION_NAME FROM RDB$RELATIONS WHERE RDB$SYSTEM_FLAG = 0 AND RDB$VIEW_BLR IS NULL ORDER BY RDB$RELATION_NAME";
    }
    if($dbtype=="mysql"){
      if($database!=""){ $complete = " from $database"; }
      $sql_statement = "show tables $complete";
    }
    if($dbtype=="pg"){
      $sql_statement = "select relname as name from pg_stat_user_tables order by relname";
    }

    if($this->con!=null){
      try {
        $this->sql=$sql_statement;
        return $this->con->query($this->sql);
      } catch(PDOException $e) {
        $this->err_msg = "Error: ". $e->getMessage();
        return false;
      }
    }
    else{
      $this->err_msg = "Error: Connection to database lost.";
      return false;
    }
  }

  //Get all databases from your server
  public function ShowDBS(){
    $this->err_msg = "";
    $sql_statement = "";
    $dbtype = $this->database_type;

    if($dbtype=="sqlsrv" || $dbtype=="mssql" || $dbtype=="ibm" || $dbtype=="dblib" || $dbtype=="odbc" || $dbtype=="sqlite2" || $dbtype=="sqlite3"){
      $sql_statement = "SELECT name FROM sys.Databases";
    }
    if($dbtype=="oracle"){
      //If the query statement fail, try with uncomment the next line:
      //$sql_statement = "select * from user_tablespaces";
      $sql_statement = "select * from v$database";
    }
    if($dbtype=="ifmx" || $dbtype=="fbd"){
      $sql_statement = "";
    }
    if($dbtype=="mysql"){
      $sql_statement = "SHOW DATABASES";
    }
    if($dbtype=="pg"){
      $sql_statement = "select datname as name from pg_database";
    }

    if($this->con!=null){
      try {
        $this->sql=$sql_statement;
        return $this->con->query($this->sql);
      } catch(PDOException $e) {
        $this->err_msg = "Error: ". $e->getMessage();
        return false;
      }
    }
    else{
      $this->err_msg = "Error: Connection to database lost.";
      return false;
    }
  }

  //Get the latest error ocurred in the connection
  public function getError(){
    return trim($this->err_msg)!="" ? "<span style='color:#FF0000;background:#FFEDED;font-weight:bold;border:2px solid #FF0000;padding:2px 4px 2px 4px;'>".$this->err_msg."</span><br />" : "";
  }

  //Disconnect database
  public function disconnect(){
    $this->err_msg = "";
    if($this->con){
      $this->con = null;
      return true;
    }else{
      $this->err_msg = "Error: Connection to database lost.";
      return false;
    }
  }

  //Build the query neccesary for the count(*) in rowcount method
  private function stmntCount($query_stmnt){
    if(trim($query_stmnt)!=""){
      $query_stmnt = trim($query_stmnt);
      $query_split = explode(" ",$query_stmnt);
      $query_flag = false;
      $query_final = "";

      for($x=0;$x<count($query_split);$x++){
        //Checking "SELECT"
        if($x==0 && strtoupper(trim($query_split[$x]))=="SELECT")
          $query_final = "SELECT count(*) ";
        if($x==0 && strtoupper(trim($query_split[$x]))!="SELECT")
          return false;

        //Checking "FROM"
        if(strtoupper(trim($query_split[$x]))=="FROM"){
          $query_final .= "FROM ";
          $query_flag = true;
          continue;
        }

        //Building the query
        if(trim($query_split[$x])!="" && $query_flag)
          $query_final .= " " . trim($query_split[$x]) . " ";
      }
      return trim($query_final);
    }
    return false;
  }
}
?>