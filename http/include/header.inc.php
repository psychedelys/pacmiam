<?php if (!isset($included)) die();  ?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<!-- Main CSS -->
<link rel="stylesheet" type="text/css" href="./style/style.css">
<link type="text/css" href="./js/jquery/css/smoothness/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
<script type="text/javascript" src="./js/jquery/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="./js/jquery/js/jquery-ui-1.8.21.custom.min.js"></script>

</head><body>
    <div id="push">
        <div id="header">
            <div class="wrapper">
                <div id="headerlinks">
                    <ul id="links">
						<?php if (isset($_SESSION['uid'])) { 
							//le user est logge
							print "<li><a href='./?pg=logoff'>logoff</a>&bullet;</li>";
							print "<li><a href='./?pg=edtuser'>".sf(ucfirst($_SESSION['username']))."</a></li>";
						} else {
							// pas encore logge
							print "<li><a href='./?pg=login'>Login</a>&bullet;</li>";
							print "<li><a href='./?pg=crtuser'>Sign In</a></li>";
						} ?>
			
                    </ul>
                </div><br>
                <div id="logo">
                    <img src="" alt="logo_pacmiam">
                </div>
                <div id="title"><a href='./'><h1>Pacmiam</h1><h3>NeXtGen</h3></a></div>
            </div>
        </div>

