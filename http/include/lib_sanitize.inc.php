<?php if (!isset($included)) die(); 

// Sanitisation library 

// Sfprint, a safe print
// All user inputed data even from db should pass here.

function sfprint($string)  {
	print sf( $string);
}

function sf($string)  {
        mb_substr($string,0,256);   // 256 Char (pas byte max... dur le sql dump)
        $string = htmlspecialchars($string, ENT_QUOTES | ENT_HTML401) ;   // html encode & " > <  classique..
        $string = str_replace('=','&#61;',$string); // html encode =  javascript pénible
      	return $string;
}



// Function valid_it 
// Validate a string type and lenght

function valid_it($field_string, $field_type, $min_length, $max_length) {
# pas de data a checker
   if(!$field_string){ return false; }

     #  initialise a c'est pas bon
    $field_ok=false;
     #  type dispo
    $data_types=array(
        "tel"=>"/^[0-9+. ]+$/",
        "zipcode"=>"/^[0-9\-a-zA-Z]+$/",
        "num"=>"/^[0-9]+$/",
        "url"=>"/^http(s)?:\/\/[a-z0-9.]+/i",
        "alpha"=>"/^[a-zA-Z]+$/",
        "alpha_spc"=>"/^[a-zA-Z ]+$/",
        "alphanum"=>"/^[a-zA-Z0-9]+$/",
        "alphanum_spc"=>"/^[a-zA-Z0-9 ]+$/",
        "print_spc" =>"/^[\w,'\- ]+$/u",
    );

        $strsize=strlen($field_string);
        if ($strsize <= $min_length)  { return false; }
        if ($strsize >= $max_length)  { return false; }

        # Check la data
        $field_ok = preg_match($data_types[$field_type], $field_string);
        if ($field_ok) { return true ;   } else {  return false; }
}

?>
