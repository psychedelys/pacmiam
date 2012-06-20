<html><head></head><body>
<h1>Creation DB</h1>
<?php
// Interdit les appels directes au pages inc
$included=true;

include_once 'include/params.inc.php';
require_once 'MDB2.php';
 
# Ne pas oublier de creer la db et le user qui va avec
#pacmiam_db#
#CREATE database pacmiam_db;
#GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP on pacmiam_db.* to pacmiam@localhost identified by 'dbpass'; 

$dsn = array(
    'phptype'  => $db_driver,
    'username' => $db_user,
    'password' => $db_pwd,
    'hostspec' => $db_server,
    'database' => $db_name,
);

$options = array(
    'debug'       => 2,
    'portability' => MDB2_PORTABILITY_ALL,
);

$mdb2 =& MDB2::connect($dsn, $options);
if (PEAR::isError($mdb2)) {
    print ('error: ');
    die($mdb2->getMessage());
}

// Decommenter pour reinitialiser les donnees
print 'Delete all SQL Table <br>';
$res =& $mdb2->query("drop tables pm_grp, pm_grp_usr, pm_miam_grp, pm_miam_usr_resp, pm_miams, pm_miamusr, pm_miamusrresp, pm_resto, pm_usr");

// Ne s'arrete pas si le drop a foire
//if (PEAR::isError($res))  { die($res->getMessage()); }



// le user... password SALT!SHA1  
//Salt = CrC 32 en hexa > 8 Char
//Sha en hexa 41 Char


print 'Create SQL Table users<br>';
$res =& $mdb2->query("CREATE TABLE IF NOT EXISTS `pm_usr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `gr44l` varchar(50) NOT NULL,
  `email` varchar(128) NOT NULL,
  `status` int(2) NOT NULL,
  `token` varchar(41) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table User'");
if (PEAR::isError($res)) {
    die($res->getMessage());
}

print 'Create SQL Table groupes<br>';
$res =& $mdb2->query("CREATE TABLE IF NOT EXISTS `pm_grp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Groupes'");
if (PEAR::isError($res)) {
    die($res->getMessage());
}


// Status 0 Membre, 1 Requested, 2 remballed, 3 Invited
print 'Create SQL Table Jonction Group_user<br>';
$res =& $mdb2->query("CREATE TABLE IF NOT EXISTS `pm_grp_usr` (
  `id_groups` int(11) NOT NULL ,
 `id_users` int(11) NOT NULL,
 `admin` boolean NOT NULL,
 `status` int(1) NOT NULL,
 PRIMARY KEY (`id_groups`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Jonction Group - User'");
if (PEAR::isError($res)) {
    die($res->getMessage());
}

print 'Create SQL Table miams<br>';
$res =& $mdb2->query("CREATE TABLE IF NOT EXISTS `pm_miams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_resto` int(11) NOT NULL,
  `datetime` timestamp NOT NULL,
  `id_user` int(11) NOT NULL,
  `deadline` timestamp NOT NULL,
  `closed` boolean NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table miams '");
if (PEAR::isError($res)) {
    die($res->getMessage());
}

print 'Create SQL Table RestO<br>';
$res =& $mdb2->query("CREATE TABLE IF NOT EXISTS `pm_resto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `nr` varchar(10) NOT NULL,
  `rue` varchar(32) NOT NULL,
  `ville` varchar(32) NOT NULL,
  `cp` varchar(12) NOT NULL,
  `pays` varchar(32) NOT NULL,
  `tel` varchar(32) NOT NULL,
  `url` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Resto'");
if (PEAR::isError($res)) {
    die($res->getMessage());
}

print 'Create SQL Table MiamUser<br>';
$res =& $mdb2->query("CREATE TABLE IF NOT EXISTS `pm_miam_grp` (
  `id_miam` int(11) NOT NULL AUTO_INCREMENT,
  `id_grp` int(11) NOT NULL,
  PRIMARY KEY (`id_miam`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Jonction Group / Miam'");
if (PEAR::isError($res)) {
    die($res->getMessage());
}

print 'Create SQL Table Miam-User-Resp<br>';
$res =& $mdb2->query("CREATE TABLE IF NOT EXISTS `pm_miam_usr_resp` (
  `id_miam` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL, 
  `status` varchar(2) NOT NULL,
  PRIMARY KEY (`id_miam`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table Jonction Miam User Response'");
if (PEAR::isError($res)) {
    die($res->getMessage());
}


print 'Insert some resto<br>';
$res =& $mdb2->query(" insert into pm_resto ( name,nr,rue,ville,cp,pays,tel,url) VALUES
                ('mousel cantine','46','montee de clausen','luxembourg','l-1343','luxembourg','+470198','www.mouselscantine.lu'); ");

if (PEAR::isError($res)) {
    die($res->getMessage());
}

$res =& $mdb2->query(" insert into pm_resto ( name,nr,rue,ville,cp,pays,tel,url) VALUES
                ('bacano','59','rue de clausen','luxembourg','l-1342','luxembourg','+43184',''); ");

if (PEAR::isError($res)) {
    die($res->getMessage());
}


$res =& $mdb2->query(" insert into pm_resto ( name,nr,rue,ville,cp,pays,tel,url) VALUES
                ('mellerefer stuff','1','rue de l\'azlette','steinzel','l-7305','luxembourg','+263321604','www.mellerefer-stuff.lu'); ");

if (PEAR::isError($res)) {
    die($res->getMessage());
}

echo "Create Some users... johndoe and thanatos with password as pass<br>";
$res =& $mdb2->query(" insert into pm_usr ( username,gr44l,email,status) VALUES
                ('johndoe','93c3e1f!8f03ef97e75a700d96f062dfc9edd728a826e48a','thanspam@trollprod.org',9); ");

if (PEAR::isError($res)) {
    die($res->getMessage());
}

$res =& $mdb2->query(" insert into pm_usr ( username,gr44l,email,status) VALUES
                ('thanatos','8d4582a9!4ea7df8ab36a257610fd58691737be6d181b8e62','thanjunk@trollprod.org',9); ");

if (PEAR::isError($res)) {
    die($res->getMessage());
}


// Disconnect
$mdb2->disconnect();

?></body></html>
