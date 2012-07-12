<?php
if (!isset($included)) die(); ?>
<h1>CreAte User</h1>

<?php
/*
 * This file sets up class-loading and the environment
 * also tests whether GMP, BCMATH, or both are defined
 * if the GMP php extension exists it is preffered
 * because it is at least an order of magnitude faster
*/
function __autoload($f)
{
    //load the interfaces first otherwise contract errors occur
    $interfaceFile = "include/classes/interface/" . $f . "Interface.php";
    if (file_exists($interfaceFile)) {
        require_once $interfaceFile;
    }
    //load class files after interfaces
    $classFile = "include/classes/" . $f . ".php";
    if (file_exists($classFile)) {
        require_once $classFile;
    }
    //if utilities are needed load them last
    $utilFile = "include/classes/util/" . $f . ".php";
    if (file_exists($utilFile)) {
        require_once $utilFile;
    }
}
if (extension_loaded('gmp') && !defined('USE_EXT')) {
    define('USE_EXT', 'GMP');
} else if (extension_loaded('bcmath') && !defined('USE_EXT')) {
    define('USE_EXT', 'BCMATH');
}

$wrongcaptcha = false;
// insertion class user
include_once "include/class/user.clss.php";
// Creation de l'objet user
$myuser = new User;
$result = false;
// Si il a poste le cookie challenge est present et doit etre bon..
if ((!empty($_POST["challenge"])) && (mb_substr($_POST["challenge"], 0, 76) == $_SESSION['challenge'])) {
    // C'est donc un post... maintenant regardon le captcha
    if ((!empty($_POST["captcha"])) && (mb_substr($_POST["captcha"], 0, 76) == $_SESSION['captcha'])) {
        $pass = $_POST["truc"];
        $bob_pub_x = $mdb2->quote($_POST["bob_pub_x"], text);
        $bob_pub_x = str_replace("'", "", $bob_pub_x);
        $bob_pub_y = $mdb2->quote($_POST["bob_pub_y"], text);
        $bob_pub_y = str_replace("'", "", $bob_pub_y);
        if (isset($pass) and isset($_POST["username"]) and isset($bob_pub_x) and isset($bob_pub_y) and isset($_SESSION['alice_priv'])) {
            $alice_priv = $_SESSION['alice_priv'];
            $g = NISTcurve::generator_192();
            $alice = new EcDH($g);
            $alice->setSecret($_SESSION['alice_priv']);
            $curve = new CurveFp($_SESSION['alice_curve_prime'], $_SESSION['alice_curve_a'], $_SESSION['alice_curve_b']);
            //new point with Bob public;
            $bob_pub = new Point($curve, $bob_pub_x, $bob_pub_y);
            //set bob pub point
            $alice->setPublicPoint($bob_pub);
            // calculate alice key
            $alice->calculateKey();
            $alice_key = $alice->getkey();
            include_once 'include/classes/aes.php';
            $password = AesCtr::decrypt($pass, $alice_key, 256);
            unset($_SESSION['alice_priv']);
            unset($_SESSION['alice_curve_prime']);
            unset($_SESSION['alice_curve_a']);
            unset($_SESSION['alice_curve_b']);
            // Result est true si les data sont bonnes et posee dans la DB
            $result = $myuser->adduser($_POST["username"], $_POST["email"], $password, $_SESSION['challenge']);
	} else {
	    $myuser->E_INPUT = true; // fait afficher les valeurs postées
	}
        } else {
            $wrongcaptcha = true; // je vais quand meme pas mettre ca dans l'objet user...
            $myuser->E_INPUT = true; // fait afficher les valeurs postées
            
        }
    }
    // Si le formulaire n'a pas ete poste ou si il est bourrer d'erreur on affiche le formulaire
    if ($result) {
        // si le post s'est bien passé.
        print "<br>user created<br>";
    } else {
        // Si le formulaire n'a pas ete poste ou si il est bourrer d'erreur on affiche le formulaire
        if ($myuser->E_INPUT) {
            print "<b>Veuillez remplir correctement le formulaire</b>";
        }
?>
	
<script language="JavaScript" type="text/javascript" src="js/jsbn.js"></script>
<script language="JavaScript" type="text/javascript" src="js/jsbn2.js"></script>
<script language="JavaScript" type="text/javascript" src="js/prng4.js"></script>
<script language="JavaScript" type="text/javascript" src="js/rng.js"></script>
<script language="JavaScript" type="text/javascript" src="js/ec.js"></script>
<script language="JavaScript" type="text/javascript" src="js/sec.js"></script>
<script language="JavaScript" type="text/javascript" src="js/aes/aes.js"></script>
<script language="JavaScript" type="text/javascript" src="js/aes/aes-ctr.js"></script>
<script language="JavaScript" type="text/javascript" src="js/aes/base64.js"></script>
<script language="JavaScript" type="text/javascript" src="js/aes/utf8.js"></script>
<script type="text/javascript">
<!--
var bob_priv = "";
var bob_pub_x = "";
var bob_pub_y = "";
var bob_key_x = "";
var bob_key_y = "";
var q = "";
var a = "";
var b = "";
var gx = "";
var gy = "";
var n = "";
var rng;

function set_ec_params(name) {
  var c = getSECCurveByName(name);

  q = c.getCurve().getQ().toString();
  a = c.getCurve().getA().toBigInteger().toString();
  b = c.getCurve().getB().toBigInteger().toString();
  gx = c.getG().getX().toBigInteger().toString();
  gy = c.getG().getY().toBigInteger().toString();
  n = c.getN().toString();

  // Changing EC params invalidates everything else
  bob_priv = "";
  bob_pub_x = "";
  bob_pub_y = "";
  bob_key_x = "";
  bob_key_y = "";
}

function get_curve() {
  return new ECCurveFp(new BigInteger(q),
    new BigInteger(a),
    new BigInteger(b));
}

function get_G(curve) {
  return new ECPointFp(curve,
    curve.fromBigInteger(new BigInteger(gx)),
    curve.fromBigInteger(new BigInteger(gy)));
}

function pick_rand() {
  var n1 = new BigInteger(n);
  var n2 = n1.subtract(BigInteger.ONE);
  var r = new BigInteger(n1.bitLength(), rng);
  return r.mod(n2).add(BigInteger.ONE);
}

function do_bob_rand() {
  var r = pick_rand();
  bob_priv = r.toString();
}

function do_bob_pub() {
  var curve = get_curve();
  var G = get_G(curve);
  var a1 = new BigInteger(bob_priv);
  var P = G.multiply(a1);
  bob_pub_x = P.getX().toBigInteger().toString();
  bob_pub_y = P.getY().toBigInteger().toString();
}

function do_bob_key() {
  var curve = get_curve();
  var P = new ECPointFp(curve,
    curve.fromBigInteger(new BigInteger(document.ecdhtest.alice_pub_x.value)),
    curve.fromBigInteger(new BigInteger(document.ecdhtest.alice_pub_y.value)));
  var a1 = new BigInteger(bob_priv);
  var S = P.multiply(a1);
  bob_key_x = S.getX().toBigInteger().toString();
  bob_key_y = S.getY().toBigInteger().toString();
}

function do_init() {
  set_ec_params("secp192r1");
  rng = new SecureRandom();
  do_bob_rand();
  do_bob_pub();
  document.ecdhtest.status.value = "Done";
}

function do_login() {
  if (document.ecdhtest.username.value == "") {
     alert("Please enter your user name.");
     return false;
  }
  if (document.ecdhtest.password.value == "") {
     alert("Please enter your password.");
     return false;
  }
  if (document.ecdhtest.email.value == "") {
     alert("Please enter your email.");
     return false;
  }
  if (document.ecdhtest.captcha.value == "") {
     alert("Please enter the captcha value.");
     return false;
  }

  //document.ecdhtest.status.value = "ok1";
  do_bob_key();
 // document.ecdhtest.status.value = "ok2";

  var formsubmit = document.getElementById("form_submit");
  formsubmit.username.value = document.ecdhtest.username.value;
  formsubmit.truc.value = Aes.Ctr.encrypt(document.ecdhtest.password.value, bob_key_x +','+ bob_key_y, 256);
  formsubmit.challenge.value = document.ecdhtest.challenge.value;
  formsubmit.bob_pub_x.value = bob_pub_x;
  formsubmit.bob_pub_y.value = bob_pub_y;
  formsubmit.email.value = document.ecdhtest.email.value;
  formsubmit.captcha.value = document.ecdhtest.captcha.value;

  formsubmit.submit();
  }
//-->
</script>

<form name="ecdhtest" id="ecdhtest" onSubmit='return false;' > 
<fieldset class="crtuser">
<p>
Login:<br>
<input type="text" name="username" id="username" size="32" <?php
        if ($myuser->E_username) {
            print "class=\"error\" ";
        }
        if ($myuser->E_INPUT) {
            print "value=\"" . sf($_POST["username"]) . "\"";
        } ?> />
<br>Password: (8 Char)<br>
<input type="password" name="password" id="password" size="64" <?php
        if ($myuser->E_password) {
            print "class=\"error\" ";
        }
        if ($myuser->E_INPUT) {
            print "value=\"" . sf($_POST["password"]) . "\"";
        } ?> /> <br/>
Email:<br>
<input type="email" name="email" id="email" size="64" <?php
        if ($myuser->E_email) {
            print "class=\"error\" ";
        }
        if ($myuser->E_INPUT) {
            print "value=\"" . sf($_POST["email"]) . "\"";
        } ?> />  <br />
<img src="./cgi-bin/captcha.php" id="captcha" /><br/>
<a href="#" onclick="document.getElementById('captcha').src='./cgi-bin/captcha.php?'+Math.random(); document.getElementById('captcha-form').focus();" id="change-image">Illisible.. un Autre</a><br/><br/>
<input type="text" name="captcha" id="captcha-form" autocomplete="off" <?php
        if ($wrongcaptcha) {
            print "class=\"error\" ";
        } ?> /><br/>
<?php
    $g = NISTcurve::generator_192();
    $alice = new EcDH($g);
    $pubPoint = $alice->getPublicPoint();
    $pubPoint = str_replace('(', '', $pubPoint);
    $pubPoint = str_replace(')', '', $pubPoint);
    list($pubPoint_X, $pubPoint_Y) = explode(',', $pubPoint);
    //
    $_SESSION['alice_priv'] = $alice->getSecret();
    //print $alice->extractPubPoint();
    list($_SESSION['alice_curve_prime'], $_SESSION['alice_curve_a'], $_SESSION['alice_curve_b']) = explode(',', $alice->extractPubPoint());
    print "<input type='hidden' name='alice_pub_x' value='$pubPoint_X'><br>\n";
    print "<input type='hidden' name='alice_pub_y' value='$pubPoint_Y'><br>\n";
    print "<input type='hidden' name='challenge' id='challenge' value='$challenge'><br>\n";

//<input type="text" name="status" id="status" value="" />
?>
<input type="submit" class="button mainaction" value="Add Me" onclick="do_login();" />
</p>
</fieldset>
</form>

<form method="post" action="#" id="form_submit">
  <div>
    <input type="hidden" name="username" id="username" />
    <input type="hidden" name="truc" id="truc" />
    <input type="hidden" name="bob_pub_x" id="bob_pub_x" />
    <input type="hidden" name="bob_pub_y" id="bob_pub_y" />
    <input type="hidden" name="email" id="email" />
    <input type="hidden" name="captcha" id="captcha" />
    <input type="hidden" name="challenge" id="challenge" />
  </div>
</form>

<script>
window.onload=do_init; 
</script>

<?php
    }
?>
