<?php if (!isset($included)) die();  ?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="./style/style.css">
</head><body>
<PRE>
 __________        _________         .__           _____   
 \______   \_____  \_   ___ \  _____ |__|____     /     \  
  |     ___/\__  \ /    \  \/ /     \|  \__  \   /  \ /  \ 
  |    |     / __ \\     \___|  Y Y  \  |/ __ \_/    Y    \
  |____|    (____  /\______  /__|_|  /__(____  /\____|__  /
                 \/        \/      \/        \/    NeXt \/Generation... 
</PRE>
<a href='./'>Main</a>&nbsp;
<?php

if (isset($_SESSION['uid'])) {
	// le user est logge
	print "<a href='./?pg=logoff'>logoff</a>&nbsp;";
	print "<a href='./?pg=edtuser'>".sf(ucfirst($_SESSION['username']))."</a>";
	} else {
	// pas encore logge
	print "<a href='./?pg=login'>login</a>&nbsp;";
	print "<a href='./?pg=crtuser'>Sign In</a>";
	}
?>
<br>
