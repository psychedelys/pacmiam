<?php

if ( ! empty ( $PHP_SELF ) && preg_match ( "/\/lib\//", $PHP_SELF ) ) {
    erreurRC(1);
}

function erreurRC($errno)
{
   header("Location: " . https://" . $host . $root . "logon.php");
   exit;
   die();
}

function db_connect($dbdriver, $dbhost, $db, $dbuser, $dbpasswd) {
  if ( empty($dbdriver) ) {
     echo "dbdriver is empty\n";
  }
  if ( strcmp ( $dbdriver, "mysql" ) == 0 ) {
      ($cdb = @mysql_connect($dbhost, $dbuser, $dbpasswd)) or erreurRC(1);
      @mysql_select_db($db,$cdb) or erreurRC(2);
      return $cdb;
  } else if ( strcmp ( $dbdriver, "pgsql" ) == 0 ) {
      $dbargs = "host=$dbhost dbname=$db user=$dbuser password=$dbpasswd";
      ($cdb = @pg_connect ( $dbargs )) or erreurRC(1);
      return $cdb;
  } else {
        erreurRC(3);
  }
}

function db_close($dbdriver, $conn) {
  if ( strcmp ( $dbdriver, "mysql" ) == 0 ) {
      @mysql_close( $conn );
  } else if ( strcmp ( $dbdriver, "pgsql" ) == 0 ) {
      pg_close ( $conn );
  } else {
        erreurRC(3);
  }
}

function db_query($dbdriver, $conn, $sqlquery ) {
  if ( strcmp ( $dbdriver, "mysql" ) == 0 ) {
      return mysql_query($sqlquery );
  } else if ( strcmp ( $dbdriver, "pgsql" ) == 0 ) {
      return pg_query($conn, $sqlquery );
  } else {
        erreurRC(3);
  }
}

function db_result($dbdriver, $result, $row, $field) {
  if ( strcmp ( $dbdriver, "mysql" ) == 0 ) {
      return mysql_result($result, $row, $field);
  } else if ( strcmp ( $dbdriver, "pgsql" ) == 0 ) {
      return pg_fetch_result($result, $row, $field);
  } else {
        erreurRC(3);
  }
}

function db_num_rows($dbdriver, $result) {
  if ( strcmp ( $dbdriver, "mysql" ) == 0 ) {
      return mysql_num_rows( $result );
  } else if ( strcmp ( $dbdriver, "pgsql" ) == 0 ) {
      return pg_num_rows( $result );
  } else {
        erreurRC(3);
  }
}

function db_fetch_array($dbdriver, $result) {
  if ( strcmp ( $dbdriver, "mysql" ) == 0 ) {
      return mysql_fetch_array( $result );
  } else if ( strcmp ( $dbdriver, "pgsql" ) == 0 ) {
      return pg_fetch_array( $result );
  } else {
        erreurRC(3);
  }
}

function db_fetch_assoc($dbdriver, $result) {
  if ( strcmp ( $dbdriver, "mysql" ) == 0 ) {
      return mysql_fetch_assoc( $result );
  } else if ( strcmp ( $dbdriver, "pgsql" ) == 0 ) {
      return pg_fetch_assoc( $result );
  } else {
        erreurRC(3);
  }
}

function db_fetch_object($dbdriver, $result) {
  if ( strcmp ( $dbdriver, "mysql" ) == 0 ) {
      return mysql_fetch_object( $result );
  } else if ( strcmp ( $dbdriver, "pgsql" ) == 0 ) {
      return pg_fetch_object( $result );
  } else {
        erreurRC(3);
  }
}

function isSSL(){
  if (isset($_SERVER['HTTPS'])){
    if($_SERVER['HTTPS'] == 1) /* Apache */ {
      return TRUE;
    } elseif ($_SERVER['HTTPS'] == 'on') /* IIS */ {
      return TRUE;
    }
  }
  if ($_SERVER['SERVER_PORT'] == 443) /* others */ {
    return TRUE;
  } else {
    return FALSE; /* just using http */
  }
}

?>
