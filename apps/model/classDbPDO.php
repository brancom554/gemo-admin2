<?php

/* ----------------------------------------------------
Created by par Jacques Jocelyn (webmaster@salaireonline.com)
L'accord de l'auteur doit etre obtenu avant de proceder a des
Modifications / duplications.

Created by Jacques Jocelyn (webmaster@salaireonline.com)
In order to modify / duplicate this program, you are required to have
the author's approval.

see tutorials : http://www.phpro.org/tutorials/Introduction-to-PHP-PDO.html
http://net.tutsplus.com/tutorials/php/why-you-should-be-using-phps-pdo-for-database-access
http://php.developpez.com/faq/sgbd/?page=pdo

-----------------------------------------------------*/

class sql extends PDO{ #This class will connect to the database and execute the query requested

	/*
	 * http://php.net/manual/en/language.oop5.patterns.php
	 * see http://stackoverflow.com/questions/2047264/use-of-pdo-in-classes
	 */
  public $dbh;   // handle of the db connexion
  private static $instance = null;
 //  private static $dsn  = 'mysql:host=127.0.0.1;dbname=mydb';
 // private static $user = 'myuser';
 // private static $pass = 'mypassword';


    public function __construct () {
    	global $iniObj;
    	$this->host = $iniObj->customerDbHost;
      $this->user = $iniObj->customerDbUser;
      $this->pass = $iniObj->customerDbPass;
      $this->db = $iniObj->customerDB;
      $this->dsn = "mysql:dbname=$this->db;host=$this->host";
 // echo "<br>host : $this->host";
       try {
          $this->dbh = new PDO(self::$this->dsn,self::$this->user,self::$this->pass,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
          $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
          $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
          $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
      catch(PDOException $e)
      {
      	// if($iniObj->debugSQL)
      	echo $e->getMessage();
      }

    }

    public static function getInstance(){
        if(!isset(self::$instance)){
            $object= __CLASS__;
            self::$instance=new $object;
        }
        return self::$instance;
    }



   function connect(){
	   try {
	   	 parent::__construct( $dns, $this->user, $this->pass );
	     $dbh = new PDO($this->dsn, $this->user, $this->pass,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
	     /*** echo a message saying we have connected ***/
	     $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	     $dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
	     $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	     // echo 'Connected to database';
	     // return($dbh);
	    }
	    catch(PDOException $e)
	    {
	      echo " Could not connect to the database server. Please try again later";
	      echo "<br><pre>";print_r($e);echo "</pre>";
	      // $e->getMessage();
	      if($iniObj->debugSQL) echo $e->getMessage();
	      else {
	      	file_put_contents(_APPS_PATH.'/logs/PDOErrors.txt', $e->getMessage(), FILE_APPEND);
	      	// $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
	      	# send email to Webmaster
	      	// return(false);
	      }
	    }
   }



  function disconnect(){
     $this->dbh = null;
  }



  #Diplay information on the sql environment if in debug mode
  function query_info() {
    $temp = ($this->last_query["end_time"] - $this->last_query["start_time"]);
    $temp *= 1000;
    $temp = number_format($temp, 3);
    echo "<table border=1 width='95%'>".
         "<tr><td colspan=8 align='center'><u><b>Your previous query statistics</b></u></TD></TR>".
         "<TR><td>SQL QUERY</TD><TD COLSPAN=7>$this->query</TD></TR>";
    if (isset($this->error)) echo "<tr><td>SQL Error</td><td colspan='7'><font color='red'>Error # $this->errorno <br> $this->error</font></td></tr>";
    else{
      echo"<tr><td>Time elapsed</td><td colspan=7>".$temp." (ms)</td></tr>".
          "<tr><td>Number of records</td><td colspan=7>". isset($this->numrows). "</td></tr>".
          "<tr><td>Number of rows affected</td><td colspan=7>$this->affected_rows</td></tr>";
      if(isset($this->explain_query)){
        echo"<tr><td colspan=8 align=center><b><u>Query Analysis</u></b></td></tr>".
            "<tr><td>SQL query</td><td align=left colspan=7>".$this->explain_query."</td></tr>".
            "<tr align=center><td><b>Table</td><td><b>Type</b></td><td><b>Possible keys</b></td><td><b>key</b></td><td><b>key len</b></td><td><b>ref</b></td><td><b>rows</b></td><td><b>extra</b></td></tr>";
        while ($this->row=mysql_fetch_array($this->explain)){
          if (isset($this->row)){
            echo
              "<tr> ".
              " <td> $this->row['table'] </td> ".
              " <td> ". $this->row['type']."</td> ".
              " <td> ". $this->row['possible_keys']."</td> ".
              " <td> ". $this->row['key']."</td> ".
              " <td> ". $this->row['key_len']."</td> ".
              " <td> ". $this->row['ref']."</td> ".
              " <td> ". $this->row['rows']."</td> ".
              " <td> ". $this->row['Extra']."</td>".
              "</TR>";
            }
          }
        }
        echo "</table><br />";
    }

  }

     /** Convenient method for mysql_fetch_object().
      * @param $result The ressource returned by query(). If NULL, the last result returned by query() will be used.
      * @return An object representing a data row.
      */
    function fetchNextObject($result = NULL){
    	if ($result == NULL) {
        	$result = $this->lastResult;
    	}
      if ($result == NULL || mysql_num_rows($result) < 1) {
        return NULL;
      }
      else{
        return mysql_fetch_object($result);
     }
    }

   function error(){ #Return the number of record retuned by the query
      if(isset($this->error)) return ("Query:" . $this->query . "\n<br>Error : ".$this->error);else return(false);
  }

   function affected_rows(){ #Return the number of record retuned by the query
      if(isset($this->affected_rows)) return ($this->affected_rows) ;else return(0) ;
  }

   function nbrecord(){ #Return the number of record retuned by the query
      if(isset($this->nbRecord)) return $this->nbRecord;
  }

  function lastID(){ #Return the las ID inserted in the table
      if(isset($this->lastID)) return $this->lastID;
   }


   function connect_error($error,$query){

      echo "<br /><br /><div align='center'><strong><span class=error>Notre serveur de base de donn&eacute;es est momentan&eacute;ment indisponible.</span></strong></div>".
         "<div align=left><br><br><strong>Vous essayer nouveau rafraichir cette page ".
           "<br />Si l'erreur persiste, nous vous prions de bien vouloir renouveler votre demande dans quelques instants.".
           "<br /><br />Nous vous remercions pour votre compr&eacute;hension.</strong></div>";
    if($ini->emailSQLError==true){
         #   Send a mail to the webmaster to notify that error + query
          $sujet=$this->domain." SQL error";
         $header="From:erreursql@".$this->domain."\n";
         $to=$this->email;
       $date=date ("l dS of F Y h:i:s A");
       $body.=" Page requested: ".$_SERVER['REQUEST_URI']."\n ";
       $body.="From : ".$_SERVER['HTTP_REFERER']."\n ";
       $body.=", \n Browser/Platform : ".$_SERVER['HTTP_USER_AGENT']."\n";
       $body.=" ip adresse : $ip, $ip1";

         $body.="\n\nLe $date \n\n Une erreur sql ($this->site)est survenue :\n$error \n\nRequete:\n$query".$msg;
         @mail($to, $sujet, $body,$header);

     }
     exit;
   }

   function getmicrotime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
  }

   function printError($errNum, $errText){
#     echo(sprintf("Error (%s): %s<p>", $errNum, $errText));
  # echo "<br>class db->print_error : error num=$errNum / text=$errText";
    if($errNum=="0"){
      unset($this->error);
    }else{
      $this->error=sprintf("Error(%s): %s", $errNum, $errText);
    }
    //exit();
  }
}

       /** Internal function to debug a MySQL query.\n
      * Show the query and output the resulting table if not NULL.
      * @param $debug The parameter passed to query() functions. Can be boolean or -1 (default).
      * @param $query The SQL query to debug.
      * @param $result The resulting table of the query, if available.
      */
    function debug($debug, $query, $result = NULL)
    {
      if ($debug === -1 && $this->defaultDebug === false)
        return;
      if ($debug === false)
        return;

      $reason = ($debug === -1 ? "Default Debug" : "Debug");
      $this->debugQuery($query, $reason);
      if ($result == NULL)
        echo "<p style=\"margin: 2px;\">Number of affected rows: ".mysql_affected_rows()."</p></div>";
      else
        $this->debugResult($result);
    }
    /** Internal function to output a query for debug purpose.\n
      * Should be followed by a call to debugResult() or an echo of "</div>".
      * @param $query The SQL query to debug.
      * @param $reason The reason why this function is called: "Default Debug", "Debug" or "Error".
      */
    function debugQuery($query, $reason = "Debug")
    {
      $color = ($reason == "Error" ? "red" : "orange");
      echo "<div style=\"border: solid $color 1px; margin: 2px;\">".
           "<p style=\"margin: 0 0 2px 0; padding: 0; background-color: #DDF;\">".
           "<strong style=\"padding: 0 3px; background-color: $color; color: white;\">$reason:</strong> ".
           "<span style=\"font-family: monospace;\">".htmlentities($query)."</span></p>";
    }

    /** Internal function to output a table representing the result of a query, for debug purpose.\n
      * Should be preceded by a call to debugQuery().
      * @param $result The resulting table of the query.
      */
    function debugResult($result)
    {
      echo "<table border=\"1\" style=\"margin: 2px;\">".
           "<thead style=\"font-size: 80%\">";
      $numFields = mysql_num_fields($result);
      // BEGIN HEADER
      $tables    = array();
      $nbTables  = -1;
      $lastTable = "";
      $fields    = array();
      $nbFields  = -1;
      while ($column = mysql_fetch_field($result)) {
        if ($column->table != $lastTable) {
          $nbTables++;
          $tables[$nbTables] = array("name" => $column->table, "count" => 1);
        } else
          $tables[$nbTables]["count"]++;
        $lastTable = $column->table;
        $nbFields++;
        $fields[$nbFields] = $column->name;
      }
      for ($i = 0; $i <= $nbTables; $i++)
        echo "<th colspan=".$tables[$i]["count"].">".$tables[$i]["name"]."</th>";
      echo "</thead>";
      echo "<thead style=\"font-size: 80%\">";
      for ($i = 0; $i <= $nbFields; $i++)
        echo "<th>".$fields[$i]."</th>";
      echo "</thead>";
      // END HEADER
      while ($row = mysql_fetch_array($result)) {
        echo "<tr>";
        for ($i = 0; $i < $numFields; $i++)
          echo "<td>".htmlentities($row[$i])."</td>";
        echo "</tr>";
      }
      echo "</table></div>";
      $this->resetFetch($result);
    }
         /** Get how many time the script took from the begin of this object.
      * @return The script execution time in seconds since the
      * creation of this object.
      */
    function getExecTime()
    {
      return round(($this->getMicroTime() - $this->mtStart) * 1000) / 1000;
    }

?>