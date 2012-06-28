<?php if (!isset($included)) die();
/*
//	_________ .__                        
//	\_   ___ \|  | _____    ______ ______
//	/    \  \/|  | \__  \  /  ___//  ___/
//	\     \___|  |__/ __ \_\___ \ \___ \ 
//	 \______  /____(____  /____  >____  >
//	        \/          \/     \/     \/ 

// Un miam est un evenement ou on mange
// Quand on le cree on est automatiquement miam_leader;

mysql> describe pm_miams;
	+----------+------------+------+-----+---------------------+-----------------------------+
	| Field    | Type       | Null | Key | Default             | Extra                       |
	+----------+------------+------+-----+---------------------+-----------------------------+
	| id       | int(11)    | NO   | PRI | NULL                | auto_increment              |
	| id_resto | int(11)    | NO   |     | NULL                |                             |
	| datetime | timestamp  | NO   |     | CURRENT_TIMESTAMP   | on update CURRENT_TIMESTAMP |
	| id_user  | int(11)    | NO   |     | NULL                |                             |
	| deadline | timestamp  | NO   |     | 0000-00-00 00:00:00 |                             |
	| closed   | tinyint(1) | NO   |     | NULL                |                             |
	+----------+------------+------+-----+---------------------+-----------------------------+
	6 rows in set (0.00 sec)
id : id du miam
id_resto : id du resto ou sera le miam
datetime : heure et date du miam
id_user : Id du createur du miam
deadline : Heure et date limite pour repondre au miam
closed : true si le miam est reserve

mysql> describe pm_miam_usr_resp;
+---------+------------+------+-----+---------+----------------+
| Field   | Type       | Null | Key | Default | Extra          |
+---------+------------+------+-----+---------+----------------+
| id_miam | int(11)    | NO   | PRI | NULL    | auto_increment |
| id_user | int(11)    | NO   |     | NULL    |                |
| status  | int(1)     | NO   |     | NULL    |                |
+---------+------------+------+-----+---------+----------------+
table de jonction user/miam
status 0 pas encore repondu
status 1 ne vient pas
status 3 vient


mysql> describe pm_miam_grp;
+---------+---------+------+-----+---------+----------------+
| Field   | Type    | Null | Key | Default | Extra          |
+---------+---------+------+-----+---------+----------------+
| id_miam | int(11) | NO   | PRI | NULL    | auto_increment |
| id_grp  | int(11) | NO   |     | NULL    |                |
+---------+---------+------+-----+---------+----------------+
2 rows in set (0.00 sec)


*/

// Creation de l'objet groupe
class	Miam
{
  public $rid;  // id du Resto
  public $gid;  // id du Groupe
  public $mid;	// id du miam
  public $miam_date; // date du miam
  public $dead_date; // date de la deadline
  public $uid; // id du miam leader

  public $E_miam_date_d; // Flags Erreur date du miam
  public $E_name;  // Flags Erreur (si true en erreur)
  public $E_gid;  // Flags Erreur groupe (si true en erreur)
  public $E_INPUT;  // Flag General erreur input
 
// Ajoute un miam dans la DB

function addmiam($gid,$rid,$date,$h,$m,$dead)
{

	// ***  Verification du group...
    $this->E_gid = true;
    if (valid_it($git,num,1,11)) { // Le nom du groupe est cool
		// est ce que le user fait partit du groupe ? si il existe pas le groupe ou il est pas membre error.
		// TODO

	    $this->gid = $name; // pas d'escape c'est un NUM.
    	$this->E_gid = false;
	}

	// *** Verification du  resto
	$this->E_rid = true;
	if (valid_it($rid,num,1,11)) { // l'id du resto est un num
		// est ce que cet id existe vraiment ?
		// TODO

		$this->rid = $rid;
		$this->E_name = false;
	}


	$this->E_date = true;
	//*** Verification de la date
	if (valid_it($date,datefr,8,10)) { // l'id du resto est un num
		$this->E_date = false;
	}

	//*** Verification de l'heure
	if (valid_it($h,num,1,2)) { // l'id du resto est un num
		$this->E_heure = false;
	}

    //*** Verification des minutes
       if (valid_it($m,num,1,2)) { // l'id du resto est un num
		$this->E_min = false;
	       }

	// mise en forme de la date. DD/MM/YYYY HH:MM
	// TODO





	//*** Verification de la dead line.
	if (valid_it($dead,num,2,5)) { // l'id du resto est un num
		// Est ce que la Dead line est pas passée ??
		// TODO

		// calcule la date 
		// TODO

	}
	
	// sinon flag "ERREUR a on" et on part false
	//$this->E_INPUT=true;
	//return false;

	return true; // Tout s'est bien passé
   
}



}
?>
