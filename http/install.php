<html><head></head><body>
<h1>Creation DB</h1>
<?php
// Interdit les appel directes au pages inc
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

print 'Create SQL Table users<br>';
$res =& $mdb2->query("CREATE TABLE IF NOT EXISTS `pm_usr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(45) NOT NULL,
  `email` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table User'");
if (PEAR::isError($res)) {
    die($res->getMessage());
}

print 'Create SQL Table groupes<br>';
$res =& $mdb2->query("CREATE TABLE IF NOT EXISTS `pm_grp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table Groupes'");
if (PEAR::isError($res)) {
    die($res->getMessage());
}

print 'Create SQL Table Jonction Group_user<br>';
$res =& $mdb2->query("CREATE TABLE IF NOT EXISTS `pm_grp_usr` (
  `id_groups` int(11) NOT NULL AUTO_INCREMENT,
 `id_users` int(11) NOT NULL,
  PRIMARY KEY (`id_groups`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table Jonction Group - User'");
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table miams '");
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table Resto'");
if (PEAR::isError($res)) {
    die($res->getMessage());
}

print 'Create SQL Table MiamUser<br>';
$res =& $mdb2->query("CREATE TABLE IF NOT EXISTS `pm_miam_grp` (
  `id_miam` int(11) NOT NULL AUTO_INCREMENT,
  `id_grp` int(11) NOT NULL,
  PRIMARY KEY (`id_miam`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table Jonction Group / Miam'");
if (PEAR::isError($res)) {
    die($res->getMessage());
}

print 'Create SQL Table Miam-User-Resp<br>';
$res =& $mdb2->query("CREATE TABLE IF NOT EXISTS `pm_miam_usr_resp` (
  `id_miam` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL, 
  `status` varchar(2) NOT NULL,
  PRIMARY KEY (`id_miam`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table Jonction Miam User Response'");
if (PEAR::isError($res)) {
    die($res->getMessage());
}


// Disconnect
$mdb2->disconnect();

?></body></html>
