<?php
if (!isset($included)) die();
error_reporting(E_ERROR);
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
if ((!empty($_POST["challenge"])) && (mb_substr($_POST["challenge"], 0, 76) == $_SESSION['challenge'])) {
    $authenticated = false;
    //data checking
    $user = pg_escape_string($_POST["login"]);
    $pass = pg_escape_string($_POST["truc"]);
    $bob_pub_x = pg_escape_string($_POST["bob_pub_x"]);
    $bob_pub_y = pg_escape_string($_POST["bob_pub_y"]);
    $row = '';
    if (isset($pass) and isset($user) and isset($bob_pub_x) and isset($bob_pub_y) and isset($_SESSION['alice_priv'])) {
        $alice_priv = $_SESSION['alice_priv'];
        print "alice_priv: '" . $_SESSION['alice_priv'] . "'<br>\n";
        print "user: '$user'<br>\n";
        print "ori_pass: '$pass'<br>\n";
        //print "bob_pub_x: '$bob_pub_x'<br>\n";
        //print "bob_pub_y: '$bob_pub_y'<br>\n";
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
        print "alice key: '" . $alice_key . "'<br>\n";
    } else {
        print "not all field defined<br>";
        //header("Location: " . "https://" . $host . $root . "index.php");
        exit;
    }
    exit;
    if (isset($pass) and isset($user)) {
        $res = & $mdb2->query("SELECT username,email,pwd FROM users WHERE username='" . $user . "';");
        if ($res->numRows() == 1) {
            $row = $res->fetchRow();
            $passwd = sf($row['gr441']);
            // le user... password SALT!SHA1
            //Salt = CrC 32 en hexa > 8 Char
            //Sha en hexa 41 Char
            $db_motdepasse = md5($_SESSION['challenge'] . $passwd);
        } else {
            //header("Location: " . "https://" . $host . $root . "index.php");
            exit;
        }
        unset($_SESSION['challenge']);
        if (strcmp($db_motdepasse, $motdepasse) == 0) {
            $authenticated = true;
            $_SESSION['user'] = sf($row['username']);
            $_SESSION['email'] = sf($row['email']);
        }
        if ($authenticated) {
            $_SESSION['authenticated'] = 1;
            header("Location: " . $_SESSION['redir']);
            exit;
        } else {
            $_SESSION['authenticated'] = 0;
            header("Location: " . "https://" . $host . $root . "index.php");
            exit;
        }
    } else {
        // No user or password !
        $_SESSION['authenticated'] = 0;
        header("Location: " . "https://" . $host . $root . "index.php");
        exit;
    }
} else {
    if (isset($_SESSION['uid']) && $_SESSION['uid'] > 1) {
        //Clean-up the session to force the disconnect.
        if (isset($_COOKIE[session_name() ])) {
            setcookie(session_name() , '', time() - 42000, '/');
        }
        session_unset();
        session_destroy();
        $_SESSION = array();
        header("Location: " . "https://" . $host . $root . "index.php");
    }
    // print logon page
    if (!isset($_SESSION['redir'])) {
        // catch the refer to redirect later
        $regex = "/^https?:\/\/" . $host . "\\" . $root . "index.php/";
        if (preg_match($regex, $_SERVER['HTTP_REFERER'])) {
            // redirect https
            $_SESSION['redir'] = $_SERVER['HTTP_REFERER'];
        } else {
            $_SESSION['redir'] = "https://" . $host . $root . "index.php";
        }
    }
?>
<script language="JavaScript" type="text/javascript" src="js/jsbn.js"></script>
<script language="JavaScript" type="text/javascript" src="js/jsbn2.js"></script>
<script language="JavaScript" type="text/javascript" src="js/prng4.js"></script>
<script language="JavaScript" type="text/javascript" src="js/rng.js"></script>
<script language="JavaScript" type="text/javascript" src="js/ec.js"></script>
<script language="JavaScript" type="text/javascript" src="js/sec.js"></script>
<script type="text/javascript">
<!--
function set_ec_params(name) {
  var c = getSECCurveByName(name);

  document.ecdhtest.q.value = c.getCurve().getQ().toString();
  document.ecdhtest.a.value = c.getCurve().getA().toBigInteger().toString();
  document.ecdhtest.b.value = c.getCurve().getB().toBigInteger().toString();
  document.ecdhtest.gx.value = c.getG().getX().toBigInteger().toString();
  document.ecdhtest.gy.value = c.getG().getY().toBigInteger().toString();
  document.ecdhtest.n.value = c.getN().toString();

  // Changing EC params invalidates everything else
  document.ecdhtest.bob_priv.value = "";
  document.ecdhtest.bob_pub_x.value = "";
  document.ecdhtest.bob_pub_y.value = "";
  document.ecdhtest.bob_key_x.value = "";
  document.ecdhtest.bob_key_y.value = "";
}
var rng;

function get_curve() {
  return new ECCurveFp(new BigInteger(document.ecdhtest.q.value),
    new BigInteger(document.ecdhtest.a.value),
    new BigInteger(document.ecdhtest.b.value));
}

function get_G(curve) {
  return new ECPointFp(curve,
    curve.fromBigInteger(new BigInteger(document.ecdhtest.gx.value)),
    curve.fromBigInteger(new BigInteger(document.ecdhtest.gy.value)));
}

function pick_rand() {
  var n = new BigInteger(document.ecdhtest.n.value);
  var n1 = n.subtract(BigInteger.ONE);
  var r = new BigInteger(n.bitLength(), rng);
  return r.mod(n1).add(BigInteger.ONE);
}

function do_bob_rand() {
  var r = pick_rand();
  document.ecdhtest.bob_priv.value = r.toString();
}

function do_bob_pub() {
  if(document.ecdhtest.bob_priv.value.length == 0) {
    alert("Please generate Bob's private value first");
    return;
  }
  var before = new Date();
  var curve = get_curve();
  var G = get_G(curve);
  var a = new BigInteger(document.ecdhtest.bob_priv.value);
  var P = G.multiply(a);
  var after = new Date();
  document.ecdhtest.bob_pub_x.value = P.getX().toBigInteger().toString();
  document.ecdhtest.bob_pub_y.value = P.getY().toBigInteger().toString();
}

function do_bob_key() {
  if(document.ecdhtest.bob_priv.value.length == 0) {
    alert("Please generate Bob's private value first");
    return;
  }
  if(document.ecdhtest.alice_pub_x.value.length == 0) {
    alert("Please compute Alice's public value first");
    return;
  }
  var before = new Date();
  var curve = get_curve();
  var P = new ECPointFp(curve,
    curve.fromBigInteger(new BigInteger(document.ecdhtest.alice_pub_x.value)),
    curve.fromBigInteger(new BigInteger(document.ecdhtest.alice_pub_y.value)));
  var a = new BigInteger(document.ecdhtest.bob_priv.value);
  var S = P.multiply(a);
  var after = new Date();
  document.ecdhtest.bob_key_x.value = S.getX().toBigInteger().toString();
  document.ecdhtest.bob_key_y.value = S.getY().toBigInteger().toString();
}

function do_init() {
  set_ec_params("secp192r1");
  if(document.ecdhtest.q.value.length == 0) set_secp160r1();
  rng = new SecureRandom();
  do_bob_rand();
  do_bob_pub();
}

function do_login() {
  if (document.ecdhtest.identifiant.value == "") {
     alert("Please enter your user name.");
     return false;
  }
  if (document.ecdhtest.motdepasse.value == "") {
     alert("Please enter your password.");
     return false;
  }
  do_bob_key();

  var formsubmit = document.getElementById("form_submit");
  formsubmit.login.value = document.ecdhtest.identifiant.value;
    
  // Change it to crypto with (document.ecdhtest.bob_key_x.value,document.ecdhtest.bob_key_y.value)
  formsubmit.truc.value = document.ecdhtest.motdepasse.value;
  formsubmit.challenge.value = document.ecdhtest.challenge.value;
  formsubmit.bob_pub_x.value = document.ecdhtest.bob_pub_x.value;
  formsubmit.bob_pub_y.value = document.ecdhtest.bob_pub_y.value;

  formsubmit.submit();
  }
//-->
</script>
    <h1> Welcome to the Pacmiam. Please authenticate yourself to access to your bubbles...</h1>
<form name="ecdhtest" id="ecdhtest" onSubmit='return false;'> 
    <fieldset class="logon">
<p>
Login:<br />
<input type="text" name="identifiant" id="identifiant" size="16" class="saisie" /><br />
Password:<br />
<input type="password" name="motdepasse" id="motdepasse" size="16" class="saisie" /><br />
<input type="hidden" name="bob_priv" value=""><br>

<!-- [Step 4] Bob's public point (<i>B = bG</i>) (X,Y):<br> -->
<input type="hidden" name="bob_pub_x" value="" ><br>
<input type="hidden" name="bob_pub_y" value=""><br>

<!-- [Step 6] Bob's secret key (<i>S = bA = baG</i>) (X,Y):<br> -->
<input type="hidden" name="bob_key_x" value=""><br>
<input type="hidden" name="bob_key_y" value=""><br>

<!-- Curve Q:<br> -->
<input type="hidden" name="q"><br>
<!-- Curve A:<br> -->
<input type="hidden" name="a"><br>
<!-- Curve B:<br> -->
<input type="hidden" name="b"><p>

<!-- G (X,Y):<br> -->
<input type="hidden" name="gx"><br>
<input type="hidden" name="gy">
<!-- N:<br> -->
<input type="hidden" name="n">

<!-- [Step 3] Alice's public point (<i>A = aG</i>) (X,Y):<br> -->
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
?>

<input type="button" value="Submit" name="submit" onclick="do_login();" />
</p>
    </fieldset>
</form>

<form method="post" action="<?php
    echo $_SERVER['PHP_SELF'] ?>?pg=login" id="form_submit">
  <div>
    <input type="hidden" name="login" id="login" />
    <input type="hidden" name="truc" id="truc" />
    <input type="hidden" name="bob_pub_x" id="bob_pub_x">
    <input type="hidden" name="bob_pub_y" id="bob_pub_y">
    <input type="hidden" name="challenge" id="challenge">
  </div>
</form></div>

<?php
}
?>

<script>
window.onload=do_init; 
</script>
