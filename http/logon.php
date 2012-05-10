<?php 
session_set_cookie_params(30 * 60, "/");
session_start(); 

error_reporting(E_ERROR);
include_once 'include/param.inc.php';
include_once 'include/function.inc.php';

$authenticated = false;

if ("valide" == $p) {

  //data checking
  $user=pg_escape_string($_POST["user"]);
  $pass=pg_escape_string($_POST["pass"]);
  if ( isset($pass) and isset($user) ) {
    $db_int = db_connect($db_driver, $db_server, $db_name, $db_user, $db_pwd );
    $query = "SELECT username,email,pwd FROM users WHERE username='" . $user . "';";
    $request = @db_query($db_driver, $db_inst, $query);
    if( db_num_rows($db_driver, $request)==1) {
      $passwd = @db_result($db_driver,$request,0,"pwd");
      $db_motdepasse=md5($_SESSION['challenge'] . $passwd);
    } else {
      db_close();
      header("Location: " . https://" . $host . $root . "logon.php");
      exit;
   }
   unset($_SESSION['challenge']);
   db_close();

   if ( strcmp ( $db_motdepasse, $motdepasse ) == 0 ) {
      $authenticated = true;
      $_SESSION['user']  = @db_result($db_driver,$request,0,"username");
      $_SESSION['email'] = @db_result($db_driver,$request,0,"email");
   }

   if ( $authenticated ) {
      header("Location: " .$_SESSION['redir']);
      exit;
   } else {
      header("Location: " . https://" . $host . $root . "logon.php");
      exit;
    }
  } else {
    // No user or password !
    header("Location: " . https://" . $host . $root . "logon.php");
    exit;
  }
} else {

  if (isset($_SESSION['id']) && $_SESSION['id']>1) {
    //Clean-up the session to force the disconnect.
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }
    session_unset();
    session_destroy();
    $_SESSION = array();

    header("Location: " . https://" . $host . $root . "logon.php");
  }

srand();
$challenge = "";
for ($i = 0; $i < 80; $i++) {
    $challenge .= dechex(rand(0, 15));
}
$_SESSION['challenge'] = $challenge;

  // print logon page
  if ( ! isset($_SESSION['redir'])){
    // catch the refer to redirect later
    $regex = "/^https?:\/\/" .$host . "\\" . $root . "index.php/";
    if (preg_match($regex,$_SERVER['HTTP_REFERER'])){
      # redirect https
      $_SESSION['redir'] = $_SERVER['HTTP_REFERER'];
    } else {
      $_SESSION['redir'] = "https://" . $host . $root . "index.php";
    }
  }
  ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
     <html>
    <head>
    <title>Pacmiam logon</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta http-equiv="pragma" content="no-cache">
<script type="text/javascript" src="js/md5.js"></script>
<script type="text/javascript">
  function login() {
    var formlogin = document.getElementById("form_login");
    if (formlogin.identifiant.value == "") {
       alert("Please enter your user name.");
       return false;
    }
    if (formlogin.motdepasse.value == "") {
       alert("Please enter your password.");
       return false;
    }
    if (formlogin.p.value == "") {
       return false;
    }
    var formsubmit = document.getElementById("form_submit");
    formsubmit.user.value = formlogin.identifiant.value;
    formsubmit.pass.value = hex_md5(formlogin.challenge.value+hex_md5(formlogin.motdepasse.value));
    formsubmit.p.value = formlogin.p.value;
    formsubmit.submit();
  }
</script>
   </head>
    <body>
    <link rel="stylesheet" href="style.css" type="text/css" media="all" >
    <h1> Welcome to the Pacmiam. Please authenticate yourself to access to your bubbles...</h1>
<form action="#" method="post" id="form_login">
    <fieldset class="logon">
<p>
Login:<br />
<input type="text" name="identifiant" id="identifiant" size="16" class="saisie" /><br />
Password:<br />
<input type="password" name="motdepasse" id="motdepasse" size="16" class="saisie" /><br />
<?php
print "<input type=\"hidden\" name=\"challenge\" id=\"challenge\" value=\"$challenge\" />";
?>
<input type="hidden" name="p" value="valide">
<input type="button" value="Submit" name="submit" onclick="login();" />
</p>
    </fieldset>
</form>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>" id="form_submit">
  <div>
    <input type="hidden" name="identifiant" id="identifiant" />
    <input type="hidden" name="motdepasse" id="motdepasse" />
    <input type="hidden" name="p" value="valide">
  </div>
</form></div>

    </body>
  </html>
<?php
}


?>
