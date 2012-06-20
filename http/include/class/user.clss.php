<?php if (!isset($included)) die();

/*
 ____ ___                           
 |    |   \______ ___________  ______
 |    |   /  ___// __ \_  __ \/  ___/
 |    |  /\___ \\  ___/|  | \/\___ \ 
 |______//____  >\___  >__|  /____  >
              \/     \/           \/ 
		  
	_________ .__                        
	\_   ___ \|  | _____    ______ ______
	/    \  \/|  | \__  \  /  ___//  ___/
	\     \___|  |__/ __ \_\___ \ \___ \ 
	 \______  /____(____  /____  >____  >
	        \/          \/     \/     \/ 

Un user... bin c'est un user quoi.



status = 1 integer
		0 User Créé mais pas validé (pas encore de pong du mail)
		1 User Suspendu
		9 User Ok

le password est dans gr44l format password SALT!SHA1  
	Salt = un CrC 32 en hexa > 8 Char
	Sha en hexa 41 Char

Le token sert pour la validation du compte


mysql> describe pm_usr;
/+----------+--------------+------+-----+---------+----------------+
| Field    | Type         | Null | Key | Default | Extra          |
+----------+--------------+------+-----+---------+----------------+
| id       | int(11)      | NO   | PRI | NULL    | auto_increment |
| username | varchar(32)  | NO   |     | NULL    |                |
| gr44l    | varchar(50)  | NO   |     | NULL    |                |
| email    | varchar(128) | NO   |     | NULL    |                |
| status   | int(2)       | NO   |     | NULL    |                |
| token    | varchar(41)  | NO   |     | NULL    |                |
+----------+--------------+------+-----+---------+----------------+
*/

// Creation de l'objet user
class	User
{
  public $id;  // id du User
  public $username;  // Nom du User
  public $password;  // Password en clair
  public $gr44l; // Password Hachémenu 
  public $email; // email du user
  public $status; // status du user
  public $token;


  public $E_username;  // Flags Erreur (si true en erreur)
  public $E_email;  // Flags Erreur (si true en erreur)
  public $E_password;  // Flags Erreur (si true en erreur)
  public $E_INPUT;  // Flag General erreur input
 

// Ajoute un groupe dans la DB
function adduser ($username,$email,$password,$salt)
{
	// Verification du name du resto
	if (valid_it($username,alphanum,4,32)) { // Le nom du user est cool
		// Ajouter ici verif sur colision de username  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		$this->username = strtolower(mysql_real_escape_string($username));
	} else {
		$this->E_username = true;
	}
	
	if (valid_it($email,email,4,128)) { //  on teste l'email

		// Si le recorde MX du dns est ok...
		list($part1,$domain)=split('@',$email);
		if(checkdnsrr($domain,'MX')) {
			$this->email = strtolower(mysql_real_escape_string($email));
    	} else {
    	$this->E_email = true;
		}
    } else {
		$this->E_email = true;
	}

    if (valid_it($password,print_spc,8,128)) { // Si l'id est pas numerique ca pue mais d'ou ca vient ?
		 // on s'en fout du format du sel, il est hashé de toutes facons on le vérifie pas
		$this->gr44l = mysql_real_escape_string(dechex(crc32($salt))."!".sha1(dechex(crc32($salt)).$password)); // le mysql escape sert a rien vraiment.
     } else {
         $this->E_password = true;
     }

     
	if (!($this->E_username || $this->E_email || $this->E_password)) { // Si pas d'erreurs alors on fourre dans la db
       	print "User ADDED<br>";

		// Géneration du token pour le mail
		$this->token = sha1(microtime().$salt);

		// connection DB
		global $mdb2;

		// Insert into DB
		$res =& $mdb2->query(" insert into pm_usr ( username,email,gr44l,status,token) VALUES 
		('$this->username','$this->email','$this->gr44l',0,'$this->token') 	;");   // Status 0 .. created but not valid.

		// On error ..	
		if (PEAR::isError($res)) {
			echo "ERRRRROR";   
 			die($res->getMessage());
		}

		$this->id =$mdb2->lastInsertID(); // Recupere le user id

       	return true; // Ca c'est bien passÃ©
	}
	
	// sinon flag "ERREUR a on" et on part false
	$this->E_INPUT=true;
	return false;


}  // Fin adduser 

}

?>
