<?php if (!isset($included)) die();  ?>

<h1>MAin Page</h1>

<a href='./?pg=dspresto'>Afficher les restos</a>
<?php if (isset($_SESSION['uid'])) {
	//le user est logge
	?>
	<a href='./?pg=crtmiam'>Cr&eacute;er un Miam</a>
	<a href='./?pg=crtresto'>Ajouter un resto</a>
	<a href='./?pg=crtgrp'>Cr&eacute;er un groupe</a> 
	<a href='./?pg=edtgrp'>Joindre un groupe</a>
	<br><br>
	<h1>Coming Miam's</h1>
	<br><br>
	<h1>Mes Groupes</h1>
<?php
printmygroups();
} ?>

