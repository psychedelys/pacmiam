<?php
if (!isset($included)) die();

session_start();

include_once 'include/function.inc.php';

if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
 }
session_destroy();
$_SESSION = array();
$_SESSION['authenticated'] = 0;

$redir = "https://" .$host . $root ."index.php";
header("location: $redir");
exit;
?>
