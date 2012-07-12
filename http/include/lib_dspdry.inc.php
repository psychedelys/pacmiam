<?php if (!isset($included)) die();

/*
functions Dont Repeat yourself

functions destinees a la presentations qui sont appelees de multiples fois
*/

// Affiche la list de mes groupes avec boutons de fonctions
function printmygroups(){
	// fetche la liste des groupes dont je suis membre 
	$fdy_tmp_grp = new Group;
	$result2 = $fdy_tmp_grp -> getgroups($_SESSION['uid'])  ; // Get group list 
	$i = 0;
	$max = count( $result2 );
    // pour chaques groupes
    while( $i < $max ) {
        // affiche le nom du group
        echo "<h3>".sf($result2[$i+1])."</h3>";
        // affiche le bouton create miam du group   
        echo "<a href=\"./?pg=crtmiam&gid=".sf($result2[$i])."\">miam</a>";
        // affiche le bouton edit si on est admin du groupe     
        if ($fdy_tmp_grp -> isgroupmaster($_SESSION['uid'],$result2[$i])) {
            echo "&nbsp;<a href=\"./?pg=edtgrp&gid=".sf($result2[$i])."\">edit</a>";
        }
        $i=$i+2;
    }
}

function printOgroups(){
    // fetche la liste des groupes dont je suis membre 
    $fdy_tmp_grp = new Group;
    $result2 = $fdy_tmp_grp -> getOgroups($_SESSION['uid'])  ; // Get group list 
    $i = 0;
    $max = count( $result2 );
    // pour chaques groupes
    while( $i < $max ) {
        // affiche le nom du group
        echo "<h3>".sf($result2[$i+1])."</h3>";
        // affiche le bouton create miam du group   
        echo "<a href=\"./?pg=edtgrp&gid=".sf($result2[$i])."\">Rejoindre</a>";
        // affiche le bouton edit si on est admin du groupe     
        $i=$i+2;
   } 
}

?>
