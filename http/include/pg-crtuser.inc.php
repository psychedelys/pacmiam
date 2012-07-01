<?php
if (!isset($included)) die(); ?>
<h1>CreAte User</h1>

<?php
// insertion class user
include_once "include/class/user.clss.php";
// Creation de l'objet user
$myuser = new User;
// Si il a poste le cookie challenge est present et doit etre bon..
if ((!empty($_POST["challenge"])) && (mb_substr($_POST["challenge"], 0, 76) == $_SESSION['challenge'])) {
    // C'est donc un post... maintenant regardon le captcha
    if ((!empty($_POST["captcha"])) && (mb_substr($_POST["captcha"], 0, 76) == $_SESSION['captcha'])) {
        // Result est true si les data sont bonnes et posee dans la DB
        $result = $myuser->adduser($_POST["username"], $_POST["email"], $_POST["password"], $_SESSION['challenge']);
    } else {
        $wrongcaptcha = true; // je vais quand meme pas mettre ca dans l'objet user...
        $myuser->E_INPUT = true; // fait afficher les valeurs postées
        
    }
}
// Si le formulaire n'a pas ete poste ou si il est bourrer d'erreur on affiche le formulaire
if ($result) {
    // si le post s'est bien passÃ©.
    print "<br>user created<br>";
} else {
    // Si le formulaire n'a pas ete poste ou si il est bourrer d'erreur on affiche le formulaire
    if ($myuser->E_INPUT) {
        print "<b>Veuillez remplir correctement le formulaire</b>";
    } ?>
	
<form action="#" method="post" id="form_login">
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
<input type="hidden" name="challenge" id="challenge" value="<?php
    print $challenge; ?>" />
<input type="submit" class="button mainaction" value="Add Me" /
</p>
</fieldset>
</form>

<?php
}
?>
