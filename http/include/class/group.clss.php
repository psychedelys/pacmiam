<?php if (!isset($included)) die();

//	  ________                           
//	 /  _____/______  ____  __ ________  
//	/   \  __\_  __ \/  _ \|  |  \____ \ 
//	\    \_\  \  | \(  <_> )  |  /  |_> >
//	 \______  /__|   \____/|____/|   __/ 
//	        \/                   |__|    
//	_________ .__                        
//	\_   ___ \|  | _____    ______ ______
//	/    \  \/|  | \__  \  /  ___//  ___/
//	\     \___|  |__/ __ \_\___ \ \___ \ 
//	 \______  /____(____  /____  >____  >
//	        \/          \/     \/     \/ 

// Un groupe est un sac avec des users dedans
// Quand on le cree on est automatiquement membre;

//+-------+-------------+------+-----+---------+----------------+
//| Field | Type        | Null | Key | Default | Extra          |
//+-------+-------------+------+-----+---------+----------------+
//| id    | int(11)     | NO   | PRI | NULL    | auto_increment |
//| name  | varchar(32) | NO   |     | NULL    |                |
//+-------+-------------+------+-----+---------+----------------+

//+-----------+------------+------+-----+---------+-------+
//| Field     | Type       | Null | Key | Default | Extra |
//+-----------+------------+------+-----+---------+-------+
//| id_groups | int(11)    | NO   | PRI | NULL    |       |
//| id_users  | int(11)    | NO   |     | NULL    |       |
//| admin     | tinyint(1) | NO   |     | NULL    |       | true si le user est group admin
//| status    | int(1)     | NO   |     | NULL    |       | 0 membre, 1 requested, 2 remballed, 3 Invited
//+-----------+------------+------+-----+---------+-------+


// Creation de l'objet groupe
class	Group
{
  public $id;  // id du Resto
  public $name;  // Nom du Resto
	
  public $E_name;  // Flags Erreur (si true en erreur)
  public $E_INPUT;  // Flag General erreur input
 
  public $groups_array;  // Array mygroups,ID

// Function qui loade un array avec les ID des groupes pour un user donne
function getgroups($usr_id) 
{
	// connection DB
	global $mdb2;
	// Fetch group name from DB
	$res =& $mdb2->query("select id_groups from pm_grp_usr where id_users='".$usr_id."';");

	// On error ..
	if (PEAR::isError($res)) {
		error("SQL ERROR 1 ".$res);
		die($res->getMessage());
	}

	$list_group=array();
    while ($row = $res->fetchRow()) {
		// for each group fetch name	
		$res2 =& $mdb2->query("select id,name from pm_grp where id='".$row['id_groups']."';");

		if (PEAR::isError($res2)) {
			error("SQL ERROR 2 ".$res2);
			die($res2->getMessage());
		}
		
		
		// a mettre dans un array	
		while ($row2 = $res2->fetchRow()) {
		$list_group[]=$row2['id'];
		$list_group[]=$row2['name'];
		}
    }
    return($list_group);
}


// Ajoute un groupe dans la DB
function addgroup ($name,$id_usr)
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
