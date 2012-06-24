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
  public $mid;  // id du Resto
  public $name;  // Nom du Resto
	
  public $E_name;  // Flags Erreur (si true en erreur)
  public $E_INPUT;  // Flag General erreur input
 
// Ajoute un miam dans la DB
function addmiam ($rid,$id_usr)
{
	// Verification du name du resto
	if (valid_it($name,print_spc,1,32)) { // Le nom du groupe est cool
 
	$this->name = strtolower(mysql_real_escape_string($name));
	} else {
	$this->E_name = true;
	}

	if (valid_it($id_usr,num,1,11)) { // Si l'id est pas numerique ca pue mais d'o ca vient ?
	} else {
        $this->E_INPUT = true;
        }

	if (!($this->E_name  )) { // Si pas d'erreurs alors 

		// connection DB
		global $mdb2;

		// Insert into DB
		$res =& $mdb2->query(" insert into pm_grp ( name) VALUES 
		('$this->name')
		;");

		// On error ..	
		if (PEAR::isError($res)) {
        	error("SQL ERROR");
 		die($res->getMessage());
		}

		$this->id =$mdb2->lastInsertID(); // Recupere le group id

                // Insert into DB la jonction USER / GROUPE
                $res =& $mdb2->query(" insert into pm_grp_usr ( id_groups, id_users, admin, status) VALUES
                ('$this->id','$id_usr',true,'0')
                ;");

                // On error ..
                if (PEAR::isError($res)) {
        			error("SQL ERROR");
                	die($res->getMessage());
                }


        	return true; // Ca c'est bien passé
	}
	
	// sinon flag "ERREUR a on" et on part false
	$this->E_INPUT=true;
	return false;


 }  

  }

?>
