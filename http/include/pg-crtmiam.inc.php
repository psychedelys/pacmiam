<?php if (!isset($included)) die();  ?>
<h1>Create Miam</h1>
<?php
include_once "include/class/miam.clss.php";

// Creation de l'objet Miam
$mymiam = new Miam;

// Si il a poste le cookie challenge est present et doit etre bon..
if ((!empty($_POST["challenge"])) && (mb_substr($_POST["challenge"],0,76)==$_SESSION['challenge'])) {
	// C'est donc un post
	$result = $mymiam-> addmiam(); // Cree le groupe pour le user
 } 

if ($result) {
	print "Miam created";
	} else {
?>
<h2>Add a Miam</h2>
<form action="./?pg=crtmiam" method="post" id="form_crtmiam">
    <fieldset class="crtmiam">
<p>
Miam pour le groupe:
<select>
<?php
  // fetche la liste des groupes dont je suis membre 
    $fdy_tmp_grp = new Group;
    $result2 = $fdy_tmp_grp -> getgroups($_SESSION['uid'])  ; // Get group list 
    $i = 0;
    $max = count( $result2 );
    // pour chaques groupes
    while( $i < $max ) {
        echo "<option>".sf($result2[$i+1])."</option>";
        // affiche le bouton create miam du group   
        // affiche le bouton edit si on est admin du groupe     
        $i=$i+2;
    }

?>
</select><br>
Date du miam:
<script>
	$(function() {
		$( "#datepicker" ).datepicker();
	});
</script>
<input type="text" id="datepicker"><br>
Heure<select>
<option selected>12</option>
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
<option>30mn avant</option>
<option>1h avant</option>
<option>1j avant</option>
<option>1sem avant</option>
</select>
<input type="hidden" name="challenge" id="challenge" value="<?php print $challenge; ?>" />
<br><input type="submit" class="button mainaction" value="Create Miam" />
</p>
</fieldset>
</form>
<?php
} 
?>
