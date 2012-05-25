<?php if (!isset($included)) die();  ?>
<h1>CreAte User</h1>
<a href='./'> Main</a>

<form action="#" method="post" id="form_login">
    <fieldset class="crtuser">
<p>
Login:<br>
<input type="text" name="identifiant" id="identifiant" size="16" class="saisie" /><br />
Password:<br>
<input type="password" name="motdepasse" id="motdepasse" size="16" class="saisie" /><br />
Email:<br>
<input type="email" name="email" id="email" size="64" class="saisie" /><br />
<input type="hidden" name="challenge" id="challenge" value="<?php print $challenge; ?>" />
<input type="hidden" name="p" value="valide">
<input type="button" value="Submit" name="submit" onclick="login();" />
</p>
    </fieldset>
</form>
