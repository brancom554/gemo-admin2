<?php

/* ----------------------------------------------------
Cr�e par Jacques Jocelyn (webmaster@salaireonline.com)
L'accord de l'auteur doit �tre obtenu avant de proc�der a des
Modifications / duplications.

Created by Jacques Jocelyn (webmaster@salaireonline.com)
In order to modify / duplicate this program, you are required to have
the author's approval.

-----------------------------------------------------*/

class sql { #This class will connect to the database and execute the query requested
   var $host="";      # Will contain the Host name (server) for the SQL Server
     var $user="";      # Will contain the user name to connect to the Host
     var $pass="";      # Will contain the password to connect to the Host
     var $db="";         # Will contain the Database name to use
   var $result;       # Where result_array will be store after query
   var $error;         # Will contain the Sql error Message
   var $linkID;      # Identifier for the SQL link
   var $query;         # Query Statement
   var $site;         # Define the current host server
   var $nbRecord;   # Number of record returned by the query
   var $lastID;      # Last ID returned by the query

    /** INTERNAL: The start time, in miliseconds.
      */
    var $mtStart;
    /** INTERNAL: The number of executed queries.
      */
    var $nbQueries;
    /** INTERNAL: The last result ressource of a query().
      */
    var $lastResult;

   function sql($ini){ #Class Constructor

      $this->host = $ini->customerDbHost;
      $this->user = $ini->customerDbUser;
      $this->pass = $ini->customerDbPass;
      $this->db = $ini->customerDB;
      $this->site = $ini->site;
      $this->domain = $ini->domain;
      $this->email = $ini->dbEmail;
      $this->debug = $ini->debugSQL;
      // echo "<pre> ini <br>";print_r($ini);
     //  echo "<pre> <br>";print_r($this);
    	$this->error="";


  }
   function connect($query,$db='',$error=''){ # This fonction will connect to the database
   	$this->mtStart    = $this->getMicroTime();

      $this->linkID=mysql_connect($this->host, $this->user, $this->pass);
    if(!$this->linkID){
      $this->connect_error(mysql_error()."\n Impossible de se connecter à la Base de données",$query);
      $this->printError(mysql_errno($this->linkID), mysql_error($this->linkID));
       $this->error= mysql_errno();
      return(false);
    }
    else {
        $status=@mysql_select_db($this->db);
      if(!$status){
         $this->connect_error(mysql_error()."\n Impossible de sélectionner la Base de données",$query);
        $this->printError(mysql_errno($this->linkID), mysql_error($this->linkID));
         $this->disconnect();
        return(false);
      }
      else return(true);
    }
  }

  # Disconnect to the SQL server
   function disconnect(){
     @mysql_close($this->linkID);
  }


    function startTransaction(){
    $result=$this->connect($this->query,$db,$error);
    $this->lastResult = $result;

    //    $this->linkID=@mysql_connect($this->host, $this->user, $this->pass);
        @mysql_query("BEGIN");
    }

    function commit(){
		IF($this->debug==true) {
       $this->last_query["start_time"] = $this->getmicrotime();
      $this->result=mysql_query("COMMIT");
      $this->errorno=mysql_errno($this->linkID);
      $this->error=mysql_error($this->linkID);
      $this->printError(mysql_errno($this->linkID), mysql_error($this->linkID));

      $this->affected_rows = @mysql_affected_rows($this->linkID);
      $this->lastID=@mysql_insert_id();
      $this->nbRecord=@mysql_numrows($this->result);
      $this->last_query["end_time"] = $this->getmicrotime();
      unset($this->explain);
      if (!strstr(strtolower($this->query), "delete ")){
        $this->explain_query="EXPLAIN ".$this->query;
        $this->explain=mysql_query($this->explain_query);
      }
      $this->last_query["connection"] = $this->linkID;
      $this->query_info();

    }
    else{
        $this->result=mysql_query("COMMIT");
        $this->printError(mysql_errno($this->linkID), mysql_error($this->linkID));
        $this->affected_rows = @mysql_affected_rows($this->linkID);
        $this->lastID=@mysql_insert_id();
        $this->nbRecord=@mysql_numrows($this->result);
        $this->disconnect();
        $this->last_query["connection"] = $this->linkID;

    }
//        @mysql_query("COMMIT");
  //      @mysql_close($this->linkID);
    }
    function rollback()    {
        @mysql_query("ROLLBACK");
        @mysql_close($this->linkID);
    }

  #Execute the query statement
    function queryTransaction($query,$db='',$error=1){ # This fonction will execute the query
    $this->query=$query;
//    $result=$this->connect($this->query,$db,$error);
//    $this->lastResult = $result;
    IF($this->debug==true) {
       $this->last_query["start_time"] = $this->getmicrotime();
      $this->result=mysql_query($this->query);
      $this->errorno=mysql_errno($this->linkID);
      $this->error=mysql_error($this->linkID);
      $this->printError(mysql_errno($this->linkID), mysql_error($this->linkID));

      $this->affected_rows = @mysql_affected_rows($this->linkID);
      $this->lastID=@mysql_insert_id();
      $this->nbRecord=@mysql_numrows($this->result);
      $this->last_query["end_time"] = $this->getmicrotime();
      unset($this->explain);
      if (!strstr(strtolower($this->query), "delete ")){
        $this->explain_query="EXPLAIN ".$this->query;
        $this->explain=mysql_query($this->explain_query);
      }
      $this->last_query["connection"] = $this->linkID;
      $this->query_info();

    }else{
        $this->result=mysql_query($this->query);
        $this->printError(mysql_errno($this->linkID), mysql_error($this->linkID));
        $this->affected_rows = @mysql_affected_rows($this->linkID);
        $this->lastID=@mysql_insert_id();
        $this->nbRecord=@mysql_numrows($this->result);
        $this->disconnect();
        $this->last_query["connection"] = $this->linkID;
    }

//	if(eregi("^SELECT", $this->query)){
    if(preg_match("/^SELECT/", $this->query)){
      $this->numrows = @mysql_num_rows($this->result);
      $this->numfields = @mysql_num_fields($this->result);
    }else{
      $this->numrows = 0;
      $this->numfields = 0;
    }

    $this->last_query["sql"] = $this->query;
    return ($this->result);
  }


  #Execute the query statement
  function query($query,$db='',$error=1){ # This fonction will execute the query

  	// echo "<BR>QUERY debug =>".$this->debugSQL;echo "<br>";exit;
    $this->query=$query;
    $result=$this->connect($this->query,$db,$error);
    $this->lastResult = $result;
    IF($this->debugSQL==true) {
       $this->last_query["start_time"] = $this->getmicrotime();
      $this->result=mysql_query($this->query);
      $this->errorno=mysql_errno($this->linkID);
      $this->error=mysql_error($this->linkID);
      $this->printError(mysql_errno($this->linkID), mysql_error($this->linkID));

      $this->affected_rows = @mysql_affected_rows($this->linkID);
      $this->lastID=@mysql_insert_id();
      $this->nbRecord=@mysql_numrows($this->result);
      $this->last_query["end_time"] = $this->getmicrotime();
      unset($this->explain);
      if (!strstr(strtolower($this->query), "delete ")){
        $this->explain_query="EXPLAIN ".$this->query;
        $this->explain=mysql_query($this->explain_query);
      }

       $this->disconnect();
       $this->last_query["connection"] = $this->linkID;
       $this->query_info();

    }else{
      if($result){
        $this->result=mysql_query($this->query);
        $this->printError(mysql_errno($this->linkID), mysql_error($this->linkID));
        $this->affected_rows = @mysql_affected_rows($this->linkID);
        $this->lastID=@mysql_insert_id();
        $this->nbRecord=@mysql_numrows($this->result);
        $this->disconnect();
        $this->last_query["connection"] = $this->linkID;
      }
    }

    if(preg_match("/^SELECT/", $this->query)){
      $this->numrows = @mysql_num_rows($this->result);
      $this->numfields = @mysql_num_fields($this->result);
    }else{
      $this->numrows = 0;
      $this->numfields = 0;
    }

    $this->last_query["sql"] = $this->query;
    return ($this->result);
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