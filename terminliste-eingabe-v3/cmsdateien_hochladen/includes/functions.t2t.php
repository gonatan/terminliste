<?php

if(!defined('CON_FRAMEWORK')) {
	die('Illegal call');
}

define("DROPDOWNCHOOSE", "Bitte wählen");

function createSel($dropdown, $select, $func_name, $currlang) {

  $arr_values = explode(";",$dropdown[$currlang]);
  $arr_values_tosave = explode(";",$dropdown[1]);
  $sel_function = "<select name='".$func_name."' id='".$func_name."' onChange=\"".$func_name."Go(this);\">";
  $sel_function .= "<option value=''>".DROPDOWNCHOOSE."</option>";
  $i = 0;
  foreach ( $arr_values as $val_function ) {
     $chosen = ""; if ( $arr_values_tosave[$i] == $select ) {$chosen = "selected";}
     $sel_function .= "<option value='".$arr_values_tosave[$i]."' ".$chosen.">".$val_function."</option>";
     $i++;
  }
  $sel_function .= "</select>";
  return $sel_function;
}

function createSelSimple($dropdown, $select, $func_name, $currlang) {

  $arr_values = explode(";",$dropdown[$currlang]);
  $arr_values_tosave = explode(";",$dropdown[1]);
  $sel_function = "<select name='".$func_name."' id='".$func_name."' onChange=\"".$func_name."Go(this);\">";
  $i = 0;
  foreach ( $arr_values as $val_function ) {
     $chosen = ""; if ( $arr_values_tosave[$i] == $select ) {$chosen = "selected";}
     $sel_function .= "<option value='".$arr_values_tosave[$i]."' ".$chosen.">".$val_function."</option>";
     $i++;
  }
  $sel_function .= "</select>";
  return $sel_function;
}

function createSelDirectSimple($dropdown, $select, $func_name) {

  $arr_values = explode(";",$dropdown);
  $sel_function = "<select name='".$func_name."' id='".$func_name."' onChange=\"".$func_name."Go(this);\">";
  $i = 0;
  foreach ( $arr_values as $val_function ) {
     $chosen = ""; if ( $arr_values[$i] == $select ) {$chosen = "selected";}
     $sel_function .= "<option value='".$arr_values[$i]."' ".$chosen.">".$val_function."</option>";
     $i++;
  }
  $sel_function .= "</select>";
  return $sel_function;
}


function createSelNum($dropdown, $select, $func_name, $currlang) {

  $arr_values = explode(";",$dropdown[$currlang]);
  $arr_values_tosave = explode(";",$dropdown[1]);
  $sel_function = "<select name='".$func_name."' id='".$func_name."' onChange=\"".$func_name."Go(this);\">";
  $sel_function .= "<option value=''>".DROPDOWNCHOOSE."</option>";
  $i = 0;
  foreach ( $arr_values as $val_function ) {
     $chosen = ""; if ( $i == $select ) {$chosen = "selected";}
     $sel_function .= "<option value='".$i."' ".$chosen.">".$val_function."</option>";
     $i++;
  }
  $sel_function .= "</select>";
  return $sel_function;
}

function createSelNumSimple($dropdown, $select, $func_name, $currlang) {

  $arr_values = explode(";",$dropdown[$currlang]);
  $arr_values_tosave = explode(";",$dropdown[1]);
  $sel_function = "<select name='".$func_name."' id='".$func_name."' onChange=\"".$func_name."Go(this);\">";
  $i = 0;
  foreach ( $arr_values as $val_function ) {
     $chosen = ""; if ( $i == $select ) {$chosen = "selected";}
     if ( $val_function != "platzhalter" ) $sel_function .= "<option value='".$i."' ".$chosen.">".$val_function."</option>";
     $i++;
  }
  $sel_function .= "</select>";
  return $sel_function;
}

function array_stripslashes(&$var)
{
	if(is_string($var))
		$var = stripslashes($var);
	else
		if(is_array($var))
			foreach($var AS $key => $value)
				array_stripslashes($var[$key]);
}

/****** Terminliste spezifisch ********/
function createCheckbox($dropdown, $select, $func_name, $currlang, $width=130 ) {

  $sel_function = "";
  $arr_values = explode(";",$dropdown[$currlang]);
  $arr_values_tosave = explode(";",$select);
  $i = 0;
  foreach ( $arr_values as $val_function ) {
  $sel_function .= "<div class='checkboxfloats' style='width:".$width."px;'><input type='checkbox' name='".$func_name.$val_function."' id='".$func_name."' value='";
  $chosen = ""; 
  foreach ( $arr_values_tosave as $destination ) {
     if ( $destination == $val_function ) {$chosen = "checked";}
  }
     $sel_function .= $val_function."' style='border: none;' ".$chosen.">&nbsp;&nbsp;".$val_function."</div>";
     $i++;
  }
  $sel_function .= "";
  return $sel_function;
}

function createCheckbox3($dropdown, $select, $func_name, $currlang, $width=250 ) {

  $sel_function = "";
  $arr_values  = $dropdown;
	//$arr_values  = explode(";",$dropdown[0]);
	//$arr_values2 = explode(";",$dropdown[$currlang]);
  $arr_values_tosave = $select;
  $i = 0;
  foreach ( $arr_values as $val_function=>$value ) {
  $sel_function .= "<div class='checkboxfloats' style='width:".$width."px;'><input type='checkbox' name='".$func_name.$val_function."' id='".$func_name."' value='";
  $chosen = ""; 
  foreach ( $arr_values_tosave as $destination ) {
     if ( $destination == $val_function ) {$chosen = "checked";}
  }
     $sel_function .= $val_function."' style='border: none;' ".$chosen.">&nbsp;&nbsp;".$value."</div>";
     $i++;
  }
  $sel_function .= "";
  return $sel_function;
}

function checkdatum($tldatum)
    {
    $tldatum_array = explode("-",$tldatum);
    if (checkdate($tldatum_array[1], $tldatum_array[2], substr($tldatum_array[0], 2) ) )
        { return true; } else { return false; }    
    }


?>