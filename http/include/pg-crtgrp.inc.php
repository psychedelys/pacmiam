<?php if (!isset($included)) die();  ?>
<h1>Create Group</h1>
<?php
include_once "include/class/group.clss.php";


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
	print "<h2>My current groups</h2>";
	$result2 = $mygrp -> getgroups($_SESSION['uid'])  ; // Get group list 

$i = 0; 
$max = count( $result2 ); 

while( $i < $max ) 
{ 
  echo sf($result2[ $i+1 ]) . '<br />'; 
    $i++; 
    $i++; 
	} 

// Ce n'est pas un post, affichage du formulaire.
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
 // fin de la page
} 

?>
