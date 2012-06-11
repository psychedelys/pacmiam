<?php if (!isset($included)) die();  ?>
<h1>CreAte resto</h1>
<?php 


// Creation de l'objet resto
class Resto
{
  public $name;  // Nom du Resto
  public $nr;
  public $rue;
  public $cp;
  public $ville;
  public $pays;
  public $tel;
  public $www;
  public $E_name;  // Flags Erreur (si true en erreur)
  public $E_nr;
  public $E_rue;
  public $E_cp;
  public $E_ville;
  public $E_pays;
  public $E_tel;
  public $E_www;
  public $E_INPUT;  // Flag General erreur input

//+-------+-------------+------+-----+---------+----------------+
//| Field | Type        | Null | Key | Default | Extra          |
//+-------+-------------+------+-----+---------+----------------+
//| id    | int(11)     | NO   | PRI | NULL    | auto_increment |
//| name  | varchar(32) | NO   |     | NULL    |                |
//| nr    | varchar(10) | NO   |     | NULL    |                |
//| rue   | varchar(32) | NO   |     | NULL    |                |
//| ville | varchar(32) | NO   |     | NULL    |                |
//| cp    | varchar(12) | NO   |     | NULL    |                |
//| pays  | varchar(32) | NO   |     | NULL    |                |
//| tel   | varchar(32) | NO   |     | NULL    |                |
//| url   | varchar(32) | NO   |     | NULL    |                |
//+-------+-------------+------+-----+---------+----------------+

function addresto ($name,$nr, $rue, $cp, $ville, $pays, $tel, $www)
{
	// Verification du name du resto
	if (valid_it($name,print_spc,1,32)) { 
	$this->name = strtolower(mysql_real_escape_string($name));
	} else {
	$this->E_name = true;
	} 

	// Verification du nr de rue (pour l instant tampis pour BIS TER etc))	
	if (valid_it($nr,num,1,10)) {
        $this->nr = mysql_real_escape_string($nr);
        } else {
        $this->E_nr = true;
        }

        // Verification de rue 
        if (valid_it($rue,print_spc,1,32)) {
        $this->rue = strtolower(mysql_real_escape_string($rue));
        } else {
        $this->E_rue = true;
        }

        // Verification de la ville
        if (valid_it($ville,print_spc,1,32)) {
        $this->ville =strtolower(mysql_real_escape_string( $ville));
        } else {
        $this->E_ville = true;
        }

        // Verification du code postal
        if (valid_it($cp,zipcode,1,32)) {
        $this->cp = strtolower(mysql_real_escape_string($cp));
        } else {
        $this->E_cp = true;
        }

	// Verification du pays
        if (valid_it($pays,alpha,4,32)) {
        $this->pays = strtolower(mysql_real_escape_string( $pays));
        } else {
        $this->E_pays = true;
        }

	// Verification du tel 
        if (valid_it($tel,tel,1,32)) {
        $tel =  str_replace(' ','',$tel);
        $tel =  str_replace('.','',$tel);
	$tel =  str_replace('+','',$tel);
	$this->tel='+'.mysql_real_escape_string($tel);
        } else {
        $this->E_tel = true;
        }
	
	// Verification www
        if (valid_it($www,url,12,32)) {  
        $this->www = mysql_real_escape_string($www); //	 pas de strtolower sur une url
        } else {
        $this->E_www = true;
        }


	// Le WWW est pas obligatoire, si tous le reste est bon on le foure dans la DBe
	if (!($this->E_name || $this->E_nr || $this->E_rue ||$this->E_ville ||$this->E_cp ||$this->E_pays ||$this->E_tel )) { // Si pas d'erreurs alors 
       		print "Resto ADDED<br>";

		// connection DB
		global $mdb2;

		// Insert into DB
		$res =& $mdb2->query(" insert into pm_resto ( name,nr,rue,ville,cp,pays,tel,url) VALUES 
		('$this->name','$this->nr','$this->rue','$this->cp','$this->ville','$this->pays','$this->tel','$this->www')
		;");

		// On error ..	
		if (PEAR::isError($res)) {
			echo "ERRRRROR";   
 			die($res->getMessage());
		}
        	return true; // Ca c'est bien passé
	}
	
	// sinon flag "ERREUR a on" et on part false
	$this->E_INPUT=true;
	return false;


 }  

  }


// Creation de l'objet Resto
	$myresto = new Resto;

// Si il a poste le cookie challenge est present et doit etre bon..
if ((!empty($_POST["challenge"])) && (mb_substr($_POST["challenge"],0,76)==$_SESSION['challenge'])) {
// C'est donc un post 
	$result = $myresto-> addresto($_POST["name"],$_POST["nr"],$_POST["rue"],$_POST["cp"],$_POST["ville"],$_POST["pays"],$_POST["tel"],$_POST["www"]);
	// Result est true si les data sont bonnes et posee dans la DB	
	// DEBUG print_r ($myresto);

}


// Si le formulaire n'a pas ete poste ou si il est bourrer d'erreur on affiche le formulaire
if (!$result) 

 {
// Pas de post, ou un post de margoulin on affiche le formulaire
?>

<?php if ($myresto->E_INPUT) {print "<b>Veuillez remplir correctement le formulaire</b>"; } ?>

<form action="./?pg=crtresto" method="post" id="form_crtresto">
    <fieldset class="crtresto">
<p>
Nom
<input type="text" name="name" id="name" size="32" <?php if ($myresto->E_name) { print "class=\"error\" ";} if ($myresto->E_INPUT) {print "value=\"".sf($_POST["name"])."\""; } ?> /><br />
Nr
<input type="text" name="nr" id="nr" size="10" <?php if ($myresto->E_nr) { print "class=\"error\" ";} if ($myresto->E_INPUT) {print "value=\""; sfprint($_POST["nr"]); print "\""; } ?> />
Rue
<input type="text" name="rue" id="rue" size="32"<?php if ($myresto->E_rue) { print "class=\"error\" ";} if ($myresto->E_INPUT) {print "value=\""; sfprint($_POST["rue"]); print "\""; } ?>/><br />
cp
<input type="text" name="cp" id="cp" size="12" <?php if ($myresto->E_cp) { print "class=\"error\" ";} if ($myresto->E_INPUT) {print "value=\""; sfprint($_POST["cp"]); print "\""; } ?>/>
ville
<input type="text" name="ville" id="ville" size="32" <?php if ($myresto->E_ville) { print "class=\"error\" ";} if ($myresto->E_INPUT) {print "value=\""; sfprint($_POST["ville"]); print "\""; } ?>/><br />
pays
<input type="text" name="pays" id="pays" size="32"<?php if ($myresto->E_pays) { print "class=\"error\" ";} if ($myresto->E_INPUT) {print "value=\""; sfprint($_POST["pays"]); print "\""; } ?>/><br />
tel
<input type="text" name="tel" id="tel" size="32" <?php if ($myresto->E_tel) { print "class=\"error\" ";} if ($myresto->E_INPUT) {print "value=\""; sfprint($_POST["tel"]); print "\""; } ?>/><br />
Site ouaib
<input type="text" name="www" id="www" size="32"<?php if ($myresto->E_www) { print "class=\"error\" ";} if ($myresto->E_INPUT) {print "value=\""; sfprint($_POST["www"]); print "\""; } ?>/><br />

<input type="hidden" name="challenge" id="challenge" value="<?php print $challenge; ?>" />
<input type="submit" class="button mainaction" value="Add resto" />
<?php
}
?>

</p>
    </fieldset>
</form>

