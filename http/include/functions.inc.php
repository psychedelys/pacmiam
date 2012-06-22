<?php if (!isset($included)) die();  

function error($message) {
$_SESSION['error']=sf($message);
print "<script type=\"text/javascript\">";
print "window.location = \"./cgi-bin/error.php\"";
print "</script>";
}
?>
