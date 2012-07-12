<?php
if (!isset($included)) die();
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
		0 User Créé mais pas encore validé (pas encore de pong du mail)
		1 User Suspendu ... 2 jaunes
		9 User Ok

le password est dans gr44l format password SALT!SHA1  
	Salt = un CrC 32 d'un random en hexa > 8 Char
	Sha en hexa 41 Char

Le token sert pour la validation du compte via le mail et pour le reset de pwd.


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
class User
{
    public $id; // id du User
    public $username; // Nom du User
    public $password; // Password en clair
    public $gr44l; // Password Hachémenu
    public $email; // email du user
    public $status; // status du user
    public $token; // Token de validation
    public $E_username; // Flags Erreur (si true en erreur)
    public $E_email; // Flags Erreur (si true en erreur)
    public $E_password; // Flags Erreur (si true en erreur)
    public $E_INPUT; // Flag General erreur input
    // Ajoute un user dans la DB
    function adduser($username, $email, $password, $salt)
    {
        // connection DB
        global $mdb2;

        // Verification du name du user
        if (valid_it($username, "alphanum", 4, 32)) { // Le nom du user est cool
			$query = ("select count(username) as doublon from pm_usr where username ='".strtolower($mdb2->quote($username, 'text'))."';");
			$res = & $mdb2->query("select count(username) as doublon from pm_usr where username =".strtolower($mdb2->quote($username, 'text')).";");
	        // On error ..
     		if (PEAR::isError($res)) {
            	error("SQL ERROR".$res.$query);
                die($res->getMessage());
            }
		
			while ($row = $res->fetchRow()) {
	        	// for each doublon
			  	if ($row['doublon']==0) {
					$this->username = strtolower($mdb2->quote($username, 'text'));
				} else {
				    $this->E_username = true;
				}
			}
	    } else {
            	$this->E_username = true;
        }

		if (valid_it($email, "email", 4, 128)) { //  on teste l'email
            // Si le recorde MX du dns est ok...
            list($part1, $domain) = split('@', $email);
            if (checkdnsrr($domain, 'MX')) {
                $this->email = strtolower($mdb2->quote($email, 'text'));
            } else {
                $this->E_email = true;
            }
        } else {
            $this->E_email = true;
        }
        if (valid_it($password, "print_spc", 8, 128)) { // Validation du pwd
            // on s'en fout du format du sel, il est hashé de toutes facons on le vérifie pas
            $hash = $password;
            // Best practice password.. hashing * 1024 ... ralentis le dehasheur
            for ($i = 1; $i <= 1024; $i++) {
                $hash = sha1(dechex(crc32($salt)) . $hash);
            }
            $this->gr44l = dechex(crc32($salt)) . "!" . $hash; // le mysql escape sert a rien vraiment.
            
        } else {
            $this->E_password = true;
        }
        if (!($this->E_username || $this->E_email || $this->E_password)) { // Si pas d'erreurs alors on fourre le user dans la db
            // Géneration du token pour le mail
            $this->token = sha1(microtime() . $salt);
            // Insert into DB
            $res = & $mdb2->query(" insert into pm_usr (username,email,gr44l,status,token) VALUES ($this->username,$this->email,'$this->gr44l',0,'$this->token');"); // Status 0 .. created but not valid.
            // On error ..
            if (PEAR::isError($res)) {
                error("SQL ERROR");
                die($res->getMessage());
            }
            $this->id = $mdb2->lastInsertID(); // Recupere le user id
            return true; // Ca c'est bien passé
            
        }
        // sinon flag "ERREUR a on" et on part false
        $this->E_INPUT = true;
        return false;
    } // Fin adduser
    
}
?>
