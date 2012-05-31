<?php if (!isset($included)) die(); 

// Sanitisation library 

// Sfprint, a safe print
// All user inputed data even from db should pass here.

function sfprint($string)  {
	mb_substr($string,0,256);   // 256 Char (pas byte max... dur le sql dump)
	$string = htmlspecialchars($string) ;   // html encode & " > <  classique..
        $string = str_replace('=','&#61;',$string); // html encode =  javascript pénible
        $string = str_replace('\'','&#39;',$string); // html encode ' javascript pénible
	print $string;
}

?>
