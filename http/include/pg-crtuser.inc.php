<?php if (!isset($included)) die();  ?>
<h1>CreAte User</h1>
<a href='./'> Main</a>

<?php 
// insertion class user
include_once "include/class/user.clss.php";

// Creation de l'objet user 
$myuser = new User;

// Si il a poste le cookie challenge est present et doit etre bon..
if ((!empty($_POST["challenge"])) && (mb_substr($_POST["challenge"],0,76)==$_SESSION['challenge'])) {
	// C'est donc un post 
    $result = $myuser-> adduser($_POST["username"],$_POST["email"],$_POST["password"],$_SESSION['challenge']);
    // Result est true si les data sont bonnes et posee dans la DB  
}

// Si le formulaire n'a pas ete poste ou si il est bourrer d'erreur on affiche le formulaire
if ($result) {
	// si le post s'est bien passé.
	print "<br>user created<br>";

} else {
	// Si le formulaire n'a pas ete poste ou si il est bourrer d'erreur on affiche le formulaire
	if ($myuser->E_INPUT) {print "<b>Veuillez remplir correctement le formulaire</b>"; } ?>
	
<form action="#" method="post" id="form_login">
<fieldset class="crtuser">
<p>
Login:<br>
<input type="text" name="username" id="username" size="32" <?php if ($myuser->E_username) { print "class=\"error\" ";} if ($myuser->E_INPUT) {print "value=\"".sf($_POST["username"])."\""; } ?> />
<br>Password: (8 Char)<br>
<input type="password" name="password" id="password" size="64" <?php if ($myuser->E_password) { print "class=\"error\" ";} if ($myuser->E_INPUT) {print "value=\"".sf($_POST["password"])."\""; } ?> /> <br/>
Email:<br>
<input type="email" name="email" id="email" size="64" <?php if ($myuser->E_email) { print "class=\"error\" ";} if ($myuser->E_INPUT) {print "value=\"".sf($_POST["email"])."\""; } ?> />  <br />
<input type="hidden" name="challenge" id="challenge" value="<?php print $challenge; ?>" />
<input type="submit" class="button mainaction" value="Add Me" /
</p>
 </fieldset>
</form>

<?php 
	}
?>
