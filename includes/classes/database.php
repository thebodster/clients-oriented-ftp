<?php

// this code is part of http://www.evolt.org/PHP-Login-System-with-Admin-Features.
// please let me know if this can't be used here.
      
class MySQLDB
{
   var $connection;         //The MySQL database connection

   /* Class constructor */
   function MySQLDB(){
      /* Make connection to database */
      $this->connection = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysql_error());
      mysql_select_db(DB_NAME, $this->connection) or die(mysql_error());
   }

   /**
    * query - Performs the given query on the database and
    * returns the result, which may be false, true or a
    * resource identifier.
    */
   function query($query){
      $a = mysql_query($query, $this->connection);
	  //echo mysql_error();
	  return $a;
   }

   function Close() {
   		mysql_close($this->connection);
   }
};

/* Create database connection */
$database = new MySQLDB;

?>