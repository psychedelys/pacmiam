<?php if (!isset($included)) die();  ?>
<h1>CreAte resto</h1>
<?php 

include_once "include/class/resto.clss.php";

// Creation de l'objet Resto
	$myresto = new Resto;

// Si il a poste le cookie challenge est present et doit etre bon..
if ((!empty($_POST["challenge"])) && (mb_substr($_POST["challenge"],0,76)==$_SESSION['challenge'])) {
// C'est donc un post 
	$result = $myresto-> addresto($_POST["name"],$_POST["nr"],$_POST["rue"],$_POST["cp"],$_POST["ville"],$_POST["pays"],$_POST["tel"],$_POST["www"]);
	// Result est true si les data sont bonnes et posee dans la DB	
	// DEBUG print_r ($myresto);

}


// Si le formulaire n'a pas ete poste ou si il est bourrer d'erreur on affiche le formulaire
if (!$result) 

 {
// Pas de post, ou un post de margoulin on affiche le formulaire
?>

<?php if ($myresto->E_INPUT) {print "<b>Veuillez remplir correctement le formulaire</b>"; } ?>

<form action="./?pg=crtresto" method="post" id="form_crtresto">
    <fieldset class="crtresto">
<p>
Nom
<input type="text" name="name" id="name" size="32" <?php if ($myresto->E_name) { print "class=\"error\" ";} if ($myresto->E_INPUT) {print "value=\"".sf($_POST["name"])."\""; } ?> /><br />
Nr
<input type="text" name="nr" id="nr" size="10" <?php if ($myresto->E_nr) { print "class=\"error\" ";} if ($myresto->E_INPUT) {print "value=\""; sfprint($_POST["nr"]); print "\""; } ?> />
Rue
<input type="text" name="rue" id="rue" size="32"<?php if ($myresto->E_rue) { print "class=\"error\" ";} if ($myresto->E_INPUT) {print "value=\""; sfprint($_POST["rue"]); print "\""; } ?>/><br />
cp
<input type="text" name="cp" id="cp" size="12" <?php if ($myresto->E_cp) { print "class=\"error\" ";} if ($myresto->E_INPUT) {print "value=\""; sfprint($_POST["cp"]); print "\""; } ?>/>
ville
<input type="text" name="ville" id="ville" size="32" <?php if ($myresto->E_ville) { print "class=\"error\" ";} if ($myresto->E_INPUT) {print "value=\""; sfprint($_POST["ville"]); print "\""; } ?>/><br />
pays
<input type="text" name="pays" id="pays" size="32"<?php if ($myresto->E_pays) { print "class=\"error\" ";} if ($myresto->E_INPUT) {print "value=\""; sfprint($_POST["pays"]); print "\""; } ?>/><br />
tel
<input type="text" name="tel" id="tel" size="32" <?php if ($myresto->E_tel) { print "class=\"error\" ";} if ($myresto->E_INPUT) {print "value=\""; sfprint($_POST["tel"]); print "\""; } ?>/><br />
Site ouaib
<input type="text" name="www" id="www" size="32"<?php if ($myresto->E_www) { print "class=\"error\" ";} if ($myresto->E_INPUT) {print "value=\""; sfprint($_POST["www"]); print "\""; } ?>/><br />

<input type="hidden" name="challenge" id="challenge" value="<?php print $challenge; ?>" />
<input type="submit" class="button mainaction" value="Add resto" />
<?php
}
?>

</p>
    </fieldset>
</form>

