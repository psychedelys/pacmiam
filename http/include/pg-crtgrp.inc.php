<?php if (!isset($included)) die();  ?>
<h1>Create Group</h1>
<?php
include_once "include/class/group.clss.php";
include_once "include/lib_dspdry.inc.php";

// Creation de l'objet Resto
$mygrp = new Group;

// Si il a poste le cookie challenge est present et doit etre bon..
if ((!empty($_POST["challenge"])) && (mb_substr($_POST["challenge"],0,76)==$_SESSION['challenge'])) {
	// C'est donc un post
	$result = $mygrp-> addgroup($_POST["name"],$_SESSION['uid']); // Cree le groupe pour le user
 } 

if ($result) {
	print "group created";
	} else {
?>
<h2>Add a group</h2>
<form action="./?pg=crtgrp" method="post" id="form_crtgrp">
    <fieldset class="crtgrp">
<p>
Nom du groupe
<input type="text" name="name" id="name" size="32" <?php if ($mygrp->E_name) { print "class=\"error\" ";} if ($mygrp->E_INPUT) {print "value=\"".sf($_POST["name"])."\""; } ?> 
/><br />
<input type="hidden" name="challenge" id="challenge" value="<?php print $challenge; ?>" />
<input type="submit" class="button mainaction" value="Add group" />
</p>
</fieldset>
</form>
<?php
   // affiche mes groupes
       print "<h2>Mes groupes</2>";
	       printmygroups();
 // fin de la page
} 
?>
