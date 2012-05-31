<?php 
// Interdit les appel directes au pages inc
$included=true;

// Gestion du Chal pour les Xsrf
srand();
$challenge = "";
for ($i = 0; $i < 76; $i++) {   // Longueur qui sent le sha
    $challenge .= dechex(rand(0, 15));
}


include_once 'include/lib_sanitize.inc.php' ;

include 'include/header.inc.php' ;

// Page par defaut main
$page="main";
// Si page "pg" donne dans l'url on la recupere
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
