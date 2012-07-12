<?php if (!isset($included)) die();  ?>
<div><h1>Display resto</h1>
<?php
if (isset($_SESSION['uid'])) { ?>
	<p><a href='./?pg=crtresto'>Ajouter un resto</a></div>
<?php 
}
print "<br>";

include_once "include/class/resto.clss.php";

// Si il y a un ID et que c'est un vrai numerique...
if ((!empty($_GET["id"]))  && (valid_it($_GET["id"],num,1,10))) {
	
	// C'est donc un display resto specifique
	print "<a href=\"./?pg=dspresto\">Index</a>";
	
	// Creation de l'objet Resto
        $myresto = new Resto;
        $res = $myresto-> loadresto($_GET["id"]); // Pas de SQLi car validit(num) est deja passe
	if ($res)  {
	print "<h2>".sf(ucfirst($myresto -> name))."</h2>";
	print sf($myresto ->nr).", ".sf($myresto -> rue)."<br>" ;
	print sf($myresto -> cp)." ".sf(ucfirst($myresto -> ville))."<br>" ;
	print sf(ucfirst($myresto -> pays))."<br>";
	print sf($myresto -> tel)."<br>";
	if (!empty($myresto -> url)) {
		print "<a href=\"http://".sf($myresto -> url)."\">".sf($myresto -> url)."</a><br>";

	};
	 } else {
	print "<h1>Hey margoulin il est invalide ton ID</h1>";
 };

 }  else {
	// Si le formulaire n'a pas ete poste ou si il est bourrer d'erreur on affiche le formulaire
	// Pas d'id Affiche l'index. 
	for ($i = 1; $i <= 26; $i++) {
		$res =& $mdb2->query("select id,name from pm_resto where name LIKE '".chr($i+96)."%' ;");
		if ($res->numRows() > 0) {
    			echo "<h2>".chr($i+64)."</h2>";  // Affiche la lettre
			while (($row = $res->fetchRow())) {
    				// Assuming MDB2's default fetchmode is MDB2_FETCHMODE_ORDERED
    				echo "<a href=\"./?pg=dspresto&id=".sf($row['id'])."\">".sf(ucfirst($row['name']))."</a><br>";
			}
		}
	 }

}

?>
