<?php
session_start();

include_once 'include/param.inc.php';
include_once 'include/function.inc.php';

if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
 }
session_destroy();
$_SESSION = array();

$redir = "https://" .$host . $root ."logon.php";
header("location: $redir");
exit;
?>
