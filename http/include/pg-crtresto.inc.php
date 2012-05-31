<?php if (!isset($included)) die();  ?>
<h1>CreAte resto</h1>
<form action="#" method="post" id="form_crtresto">
    <fieldset class="crtresto">
<p>
<?php sfprint('Test safe print = & < > \' " '); ?><br>
Nom
<input type="text" name="nom" id="nom" size="32" class="saisie" /><br />
Nr
<input type="text" name="nr" id="nr" size="10" class="saisie" />
Rue
<input type="text" name="rue" id="rue" size="32" class="saisie" /><br />
cp
<input type="text" name="rue" id="cp" size="12" class="saisie" />
ville
<input type="text" name="rue" id="ville" size="32" class="saisie" /><br />
pays
<input type="text" name="rue" id="pays" size="32" class="saisie" /><br />
tel
<input type="text" name="rue" id="tel" size="32" class="saisie" /><br />
Site ouaib
<input type="text" name="rue" id="www" size="32" class="saisie" /><br />

<input type="hidden" name="challenge" id="challenge" value="<?php print $challenge; ?>" />
<input type="hidden" name="p" value="valide">
<input type="button" value="Submit" name="submit" onclick="login();" />


</p>
    </fieldset>
</form>

