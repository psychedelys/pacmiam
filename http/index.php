<?php 
// Interdit les appel directes au pages inc
$included=true;

// Set full Unicode work
mb_language('uni');
mb_internal_encoding('UTF-8');

include_once 'include/lib_sanitize.inc.php' ;

// Start des sessions
session_start();



// Gestion du Chal pour les Xsrf
srand();
$challenge = "";
for ($i = 0; $i < 76; $i++) {   // Longueur qui sent le sha
    $challenge .= dechex(rand(0, 15));
}


// Connection a la database
include_once 'include/params.inc.php';
require_once 'MDB2.php';
$dsn = array(
    'phptype'  => $db_driver,
    'username' => $db_user,
    'password' => $db_pwd,
    'hostspec' => $db_server,
    'database' => $db_name,
);

$options = array(
    'debug'       => 2,
    'portability' => MDB2_PORTABILITY_ALL,
);

// Creation Connection
$mdb2 =& MDB2::connect($dsn, $options);
if (PEAR::isError($mdb2)) {
    print ('error: ');
    die($mdb2->getMessage());
}

// Db in utf8 aussi
$mdb2 -> setCharset('utf8');
$mdb2->setFetchMode(MDB2_FETCHMODE_ASSOC); // retourne les nom des champs mappes 

// Debu  de la page
include 'include/header.inc.php' ;


// Page par defaut main
$page="main";
// Si page "pg" donne dans l'url on la recupere (PG pour page sans voyelles)
if (!empty($_GET["pg"])) $page=preg_replace('/[^a-z]+/', '', mb_substr($_GET["pg"],0,10)); 

switch ($page) {
    case "crtmiam":  // Creation d'un Miam
	include 'include/pg-crtmiam.inc.php';
        break;
    case "dspmiam":   // Affichage d'un Miam
	include 'include/pg-dspmiam.inc.php';
    case "login":
        break;
    case "loggoff":
        break;
    case "crtresto":  // Ajouter un resto
	include 'include/pg-crtresto.inc.php';
	break;
    case "dspresto"; // Affiche les resto
         include 'include/pg-dspresto.inc.php';
	break;
     case "crtuser":  // Creer un User
        include 'include/pg-crtuser.inc.php';
        break;
     case "crtgrp":  // Creer un Group
        include 'include/pg-crtgrp.inc.php';
        break;
    default:     // parametre bidonne ou absent, Page d'acceuil
	include 'include/pg-main.inc.php' ;
	break;
}

include 'include/footer.inc.php' ;

// sauvegarde du challenge
$_SESSION['challenge'] = $challenge;

?>
