<?php if (!isset($included)) die();  ?>
<h1>Create Miam</h1>
<?php
include_once "include/class/miam.clss.php";

// Creation de l'objet Miam
$mymiam = new Miam;

// Si il a poste le cookie challenge est present et doit etre bon..
if ((!empty($_POST["challenge"])) && (mb_substr($_POST["challenge"],0,76)==$_SESSION['challenge'])) {
	// C'est donc un post
	$result = $mymiam-> addmiam($_POST["gid"],$_POST["rid"],$_POST["miamdate"],$_POST["heure"],$_POST["min"],$_POST["deadline"]); // Cree le groupe pour le user
} 

if ($result) {
	print "Miam created";
	} else { // Affiche le formulaire.
?>

<h2>Add a Miam</h2>

<script>
$(function() {
	$( "#datepicker" ).datepicker({ 
		dayNamesMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"], 
		minDate: new Date(<?php echo date("Y,n - 1,d"); ?>), 
		dateFormat: "dd/mm/yy", 
		monthNames: ["Janvier","Fevrier","Mars","Avril","Mai","Juin","Juillet","Aout","Septembre","Octobre","Novembre","Decembre"] 
	});
}); 
</script>


<form action="./?pg=crtmiam" method="post" id="form_crtmiam">
<fieldset class="crtmiam"><p>

Miam pour le groupe: <select>
<?php
// Si le group est preselectionne et correct on l'impose
if ((!empty($_GET["gid"])) && (valid_it($_GET["gid"],num,1,11))) { // Le nom du groupe est cool
	print "<option selected>".$_GET["gid"]."</option>";  // pas de SF ici le groupe est un NUM

} 

// on propose la liste de groupes.
// fetche la liste des groupes dont je suis membre 
    $fdy_tmp_grp = new Group;
    $result2 = $fdy_tmp_grp -> getgroups($_SESSION['uid'])  ; // Get group list 
    $i = 0;
    $max = count( $result2 );
    // pour chaques groupes
    while( $i < $max ) {
        echo "<option value=".sf($result2[$i]).">".sf($result2[$i+1])."</option>";
        // affiche le bouton create miam du group   
        // affiche le bouton edit si on est admin du groupe     
        $i=$i+2;
   }

?>
</select><br>


On mange chez :<select>
<?php
    $res =& $mdb2->query("select id,name from pm_resto order by name;");
	if ($res->numRows() > 0) {
		while (($row = $res->fetchRow())) {
       		// Assuming MDB2's default fetchmode is MDB2_FETCHMODE_ORDERED
        	echo "<option value=".sf($row['id']).">".sf(ucfirst($row['name']))."</option>";
		}
	}
?>
</select>
Date du miam:
<input type="text" id="datepicker"><br>
Heure<select><option selected>12</option>
<?php
for ($i = 0; $i <= 23; $i++) {
    echo "<option>".$i."</option>";
	}
?>
</select>
:
<select>
<option>15</option>
<option>30</option>
<option>45</option>
<option>00</option>
</select>

Dead Line:
<select>
<option value="30">30mn avant</option>
<option value="60">1h avant</option>
<option value="1440">1j avant</option>
<option value="2880">2j avant</option>
<option value="10080">1sem avant</option>
</select>

<input type="hidden" name="challenge" id="challenge" value="<?php print $challenge; ?>" />
<br><input type="submit" class="button mainaction" value="Create Miam" />
</p>
</fieldset>
</form>
<?php
} 
?>
