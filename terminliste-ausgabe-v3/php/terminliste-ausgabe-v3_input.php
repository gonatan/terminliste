?>

<style type="text/css">
	fieldset {margin-top: 15px; background-color: #F4F4F4; padding: 0;}
	fieldset table {width: 100%;}
	fieldset table td input.follow { margin-left: 5px;}
	fieldset table td input.follow2 { margin-left: 20px;}
	h4 { font-size: 13px; font-weight: bold;}
	.silverline {border-bottom: 1px solid silver; }
	.silverlinetop {border-top: 1px solid silver; }
	.darkgray {background-color: #e4e4e4}
	input, select {margin-right: 10px;}
	div.checkbox_wrapper { display: inline; }
	.morebutton {margin-right: 5px; padding: 0 5px; background: #0060B1; color: white; text-align: center; border: 1px solid black;}
	.morebutton2 {margin-right: 5px; padding: 0 5px; background: white; color: #0060B1; text-align: center;border: 1px solid black;}
	span.showall {display: none;}
</style>

<?php

/*********************************************** 
* CONTENIDO MODUL - INPUT
* 
* Modulname      :     Terminliste Ausgabe
* Version        :     3.0
* Author         :     schlaucher (Original 4.8 version)
* 4.9 Adaptation :     Viktor Lehmann, TONE2TONE
* Created        :     12-07-2006 (schlaucher)
* Modified       :     20.08.2015 (T2T)
************************************************/ 

// Includes
if ( !function_exists("getChildPicas") ) { cInclude("module", "js.input.alr.php"); }
if ( !function_exists("fncBuildCategorySelect") ) { cInclude("module", "functions.input.helper.gw.php"); }

$MOD[categories]   = explode(";", getEffectiveSetting('terminliste', 'categories', ''));


// Initialization
$bDebug          = false;
$iDataStart      = 1000; // Startwert fuer dynamisch generierte CMS_VAR Elemente !!!Muss im Output gleich sein!!!
$sSubmitLink     = '<a href="javascript: if (document.tplcfgform.send) {document.tplcfgform.send.value = 0}; document.tplcfgform.submit();"><img class="submitbutton" src="images/submit.gif" title="'.mi18n("Save changes").'" /></a>';

// Start der Output-Ausgabe
$db = cRegistry::getDb();
$cfg = cRegistry::getConfig();
$client = cRegistry::getClientId();
$cfgClient = cRegistry::getClientConfig();

$show_cat_none      = ( "CMS_VALUE[22]" == "none" ) ? "checked" : ""; 
$show_cat_checkbox  = ( "CMS_VALUE[22]" == "checkbox" ) ? "checked" : ""; 
$show_cat_dropdown  = ( "CMS_VALUE[22]" == "dropdown" ) ? "checked" : ""; 

$adddir_checked_none = ( "CMS_VALUE[17]" == "none" ) ? "checked" : ""; 
$adddir_checked_below = ( "CMS_VALUE[17]" == "below" ) ? "checked" : ""; 
$adddir_checked_select = ( "CMS_VALUE[17]" == "select" ) ? "checked" : ""; 

$passed_checked_none       = ( "CMS_VALUE[2]" == "keine" ) ? "checked" : ""; 
$passed_checked_all        = ( "CMS_VALUE[2]" == "alle" )  ? "checked" : ""; 
$passed_checked_week       = ( "CMS_VALUE[2]" == "woche" ) ? "checked" : "";
$passed_checked_month      = ( "CMS_VALUE[2]" == "monat" ) ? "checked" : "";
$passed_checked_year       = ( "CMS_VALUE[2]" == "jahr" ) ? "checked" : "";
$passed_checked_lastdays   = ( "CMS_VALUE[2]" == "lastdays" ) ? "checked" : "";

$coming_checked_none       = ( "CMS_VALUE[3]" == "keine" ) ? "checked" : ""; 
$coming_checked_all        = ( "CMS_VALUE[3]" == "alle" )  ? "checked" : ""; 
$coming_checked_week       = ( "CMS_VALUE[3]" == "woche" ) ? "checked" : "";
$coming_checked_month      = ( "CMS_VALUE[3]" == "monat" ) ? "checked" : "";
$coming_checked_year       = ( "CMS_VALUE[3]" == "jahr" ) ? "checked" : "";
$coming_checked_days       = ( "CMS_VALUE[3]" == "tage" ) ? "checked" : "";
$coming_checked_timeframe  = ( "CMS_VALUE[3]" == "datum" ) ? "checked" : "";

$sort_asc   = ( "CMS_VALUE[16]" == "ASC" ) ? "checked" : "";
$sort_desc  = ( "CMS_VALUE[16]" == "DESC" ) ? "checked" : "";

$weekdays_short = ( "CMS_VALUE[7]" == "kurz" ) ? "checked" : "";
$weekdays_long  = ( "CMS_VALUE[7]" == "lang" ) ? "checked" : "";
$weekdays_none  = ( "CMS_VALUE[7]" == "keine" ) ? "checked" : "";

$MOD_TL_zeitraum1      = ("CMS_VALUE[2]" == '') ? 'keine' : "CMS_VALUE[2]"; 
$MOD_TL_zeitraum2      = ("CMS_VALUE[3]" == '') ? 'alle' : "CMS_VALUE[3]"; 
$MOD_TL_last_days      = ("CMS_VALUE[4]" == '') ? '' : "CMS_VALUE[4]"; 
$MOD_TL_von_datum      = ("CMS_VALUE[5]" == '') ? '' : "CMS_VALUE[5]"; 
$MOD_TL_bis_datum      = ("CMS_VALUE[6]" == '') ? '' : "CMS_VALUE[6]"; 
$MOD_TL_wtag           = ("CMS_VALUE[7]" == '') ? '' : "CMS_VALUE[7]"; 

$MOD_TL_group          = ("CMS_VALUE[8]" == '') ? '' : 'checked';
$MOD_TL_link           = ("CMS_VALUE[9]" == '') ? '' : 'checked';
$MOD_TL_htmlyn         = ("CMS_VALUE[10]" == '') ? '' : 'checked';
$MOD_TL_show_monat     = ("CMS_VALUE[12]" == '') ? '' : 'checked';

$MOD_TL_plustag        = ("CMS_VALUE[13]" == '') ? 0 : "CMS_VALUE[13]"; 
$MOD_TL_z2_tage        = ("CMS_VALUE[14]" == '') ? 0 : "CMS_VALUE[14]"; 
$MOD_TL_anzahl_termine = ("CMS_VALUE[15]" == '') ? '-1' : "CMS_VALUE[15]"; 
$MOD_TL_plustag        = ("CMS_VALUE[13]" == '') ? 0 : "CMS_VALUE[13]"; 
$MOD_TL_sort           = ("CMS_VALUE[16]" == '') ? 'ASC' : "CMS_VALUE[16]"; 
$MOD_TL_add_tree       = ("CMS_VALUE[17]" == '') ? 'keine' : "CMS_VALUE[17]"; 

$MOD_TL_add_tree_ids   = "CMS_VALUE[18]";

// find Smarty-Templates which belong to THIS module by scanning the appropriate subdir
$module = new cModuleHandler($cCurrentModule);
$tplFiles = $module->getAllFilesFromDirectory('template');
array_multisort($tplFiles, SORT_ASC, SORT_STRING);

// construct the HTML table
$filltable = array();
$fillrow   = array();
$table = new cHTMLTable();
$table->setWidth("100%");
//first row with putting module on-/offline
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_online"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$select = new cHTMLSelectElement("CMS_VAR[0]");
$select->autofill(array(""=>mi18n("please_choose"), "true"=>"aktiv", "false"=>"inaktiv"));
$select->setDefault("CMS_VALUE[0]");
$td->setContent($select.$sSubmitLink);
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

//start next row with choice of show/hide setting boxes
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_behaviour"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$checkbox = new cHTMLCheckbox("CMS_VAR[1]", "true");
$checkbox->setLabelText( mi18n("keep_menu_open") );
$checkbox->setChecked( "CMS_VALUE[1]" );
$td->setContent($checkbox);
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

//determine output mode: modul config or FEU form?
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_determineoutput"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$select = new cHTMLSelectElement("CMS_VAR[21]");
$select->autofill(array("module"=>"Termine ausgeben wie unten konfiguriert", "feuform"=>"Frontenduser-Formular anzeigen", "calendar"=>"Kalender anzeigen"));
$select->setDefault("CMS_VALUE[21]");
$td->setContent($select.$sSubmitLink);
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

// put all rows in the table and echo this first table
$table->setContent($filltable);
echo $table->render();

// construct the first table to include in a fieldset
$faqclass = ( "CMS_VALUE[1]" ) ? "showall" : "showmore";

$filltable = array();
$table = new cHTMLTable();
//first row with categories
$fillrow   = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_category_main"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$input = buildCategorySelect("CMS_VAR[11]", "CMS_VALUE[11]", "0");
$td->setContent($input);
$td->setClass("silverline");
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

//start next row with additional category choices
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_adddir"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$input = new cHTMLRadiobutton("CMS_VAR[17]", 'none', '', $adddir_checked_none);
$input->setLabelText(mi18n("value_none"));
$td->setContent($input);
$input2 = new cHTMLRadiobutton("CMS_VAR[17]", 'below', '', $adddir_checked_below);
$input2->setLabelText(mi18n("value_below"));
$td->setContent($input."  ".$input2);
$input3 = new cHTMLRadiobutton("CMS_VAR[17]", 'select', '', $adddir_checked_select);
$input3->setLabelText(mi18n("value_select"));
$td->setContent($input."  ".$input2."  ".$input3);
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

// construct the additional categories select
$fillrow   = array();
$table = new cHTMLTable();
//first row with categories
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent("");
$fillrow[] = $td;
$td = new cHTMLTableData();
$select = fncBuildCategorySelect("adddir".$cCurrentContainer, "CMS_VALUE[18]" , 0, "fncUpdateSel( 'adddir".$cCurrentContainer."', '"."CMS_VAR[18]"."');", "10", "multiple", false, 0, false);
$input = new cHTMLHiddenField("CMS_VAR[18]", "CMS_VALUE[18]");
//$td->setContent(fncAddMultiSelJS().$select.$input); // geändert nach Umlagerung der Funktion in andere Datei
$td->setContent($select.$input);
//$td->setClass("silverline");
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

//start next row with Results Template info
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_template"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$select = new cHTMLSelectElement("CMS_VAR[24]");

$option = new cHTMLOptionElement(mi18n("please_choose"), "");
$select->appendOptionElement($option);
foreach ($tplFiles AS $key=>$value) {
	$value = str_replace($tplpath, "", $value);

	$option = new cHTMLOptionElement($value, $value);
    if ("CMS_VALUE[24]" == $value) {
        $option->setSelected(true);
    }
    $select->appendOptionElement($option);
}

$td->setContent($select);
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

//start next row with Calendar Template info
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_template_calendar"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$select = new cHTMLSelectElement("CMS_VAR[27]");

$option = new cHTMLOptionElement(mi18n("please_choose"), "");
$select->appendOptionElement($option);
foreach ($tplFiles AS $key=>$value) {
	$value = str_replace($tplpath, "", $value);

	$option = new cHTMLOptionElement($value, $value);
    if ("CMS_VALUE[27]" == $value) {
        $option->setSelected(true);
    }
    $select->appendOptionElement($option);
}

$td->setContent($select);
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

//start next row with FEU-Search-Template info
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_template_feuform"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$select = new cHTMLSelectElement("CMS_VAR[28]");

$option = new cHTMLOptionElement(mi18n("please_choose"), "");
$select->appendOptionElement($option);
foreach ($tplFiles AS $key=>$value) {
	$value = str_replace($tplpath, "", $value);

	$option = new cHTMLOptionElement($value, $value);
    if ("CMS_VALUE[28]" == $value) {
        $option->setSelected(true);
    }
    $select->appendOptionElement($option);
}

$td->setContent($select);
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

// echo this fieldset, add legend and JS-functionality first
$table->setContent($filltable);

$fieldset = new cHTMLFieldset();
$fieldset->appendContent('<legend class="'.$faqclass.'"><h4><span class="morebutton '.$faqclass.'">&raquo;</span>'.mi18n("Main configuration").'</h4></legend>');
$fieldset->appendContent('<div class="answer">');
$fieldset->appendContent($table);
$fieldset->appendContent('</div>');
echo $fieldset->render();

//********************************
// start all over with fresh table
//********************************

$filltable = array();
$table = new cHTMLTable();

//Show passed Dates
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_showpasseddates"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$input = new cHTMLRadiobutton("CMS_VAR[2]", 'keine', '', $passed_checked_none);
$input->setLabelText(mi18n("value_none"));
//$td->setContent($input);
$input2 = new cHTMLRadiobutton("CMS_VAR[2]", 'alle', '', $passed_checked_all);
$input2->setLabelText(mi18n("value_all"));
//$td->setContent($input."  ".$input2);
$input3 = new cHTMLRadiobutton("CMS_VAR[2]", 'woche', '', $passed_checked_week);
$input3->setLabelText(mi18n("value_week"));
$input4 = new cHTMLRadiobutton("CMS_VAR[2]", 'monat', '', $passed_checked_month);
$input4->setLabelText(mi18n("value_month"));
$input5 = new cHTMLRadiobutton("CMS_VAR[2]", 'jahr', '', $passed_checked_year);
$input5->setLabelText(mi18n("value_year"));
$input6 = new cHTMLRadiobutton("CMS_VAR[2]", 'lastdays', '', $passed_checked_lastdays);
$input6->setLabelText(mi18n("value_lastdays"));
$input7 = new cHTMLTextbox("CMS_VAR[4]", "CMS_VALUE[4]",'4','3','max_articleamount',false,null,'');
$td->setContent($input.$input2."<br>".$input3.$input4.$input5."<br>".$input6.$input7.mi18n("amount_of_days"));
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

//Show coming Dates
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_showcomingdates"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$input = new cHTMLRadiobutton("CMS_VAR[3]", 'keine', '', $coming_checked_none);
$input->setLabelText(mi18n("value_none"));
$td->setContent($input);
$input2 = new cHTMLRadiobutton("CMS_VAR[3]", 'alle', '', $coming_checked_all);
$input2->setLabelText(mi18n("value_all"));
$td->setContent($input."  ".$input2);
$input3 = new cHTMLRadiobutton("CMS_VAR[3]", 'woche', '', $coming_checked_week);
$input3->setLabelText(mi18n("value_week"));
$input4 = new cHTMLRadiobutton("CMS_VAR[3]", 'monat', '', $comingd_checked_month);
$input4->setLabelText(mi18n("value_month"));
$input5 = new cHTMLRadiobutton("CMS_VAR[3]", 'jahr', '', $coming_checked_year);
$input5->setLabelText(mi18n("value_year"));
$input6 = new cHTMLRadiobutton("CMS_VAR[3]", 'tage', '', $coming_checked_days);
$input6->setLabelText(mi18n("value_days"));
$input7 = new cHTMLTextbox("CMS_VAR[14]", "CMS_VALUE[14]",'4','3','max_articleamount',false,null,'');
$input8 = new cHTMLTextbox("CMS_VAR[5]", "CMS_VALUE[5]",'10','10','max_articleamount',false,null,'');
$input9 = new cHTMLTextbox("CMS_VAR[6]", "CMS_VALUE[6]",'10','10','max_articleamount',false,null,'');
$input10 = new cHTMLRadiobutton("CMS_VAR[3]", 'datum', '', $coming_checked_timeframe);
$input10->setLabelText(mi18n("value_timeframe"));
$td->setContent($input.$input2."<br>".$input3.$input4.$input5." + ".$input7.mi18n("days")."<br>".$input10." ".$input8." - ".$input9." ");
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;


//Amount of shown Dates
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_amountofdates"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$input = new cHTMLTextbox("CMS_VAR[15]", "CMS_VALUE[15]",'4','3','max_articleamount',false,null,'');
$td->setContent("Maximal:".$input);
//$td->setClass("silverline");
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

//Teaser Handling
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_teaser"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$checkbox = new cHTMLCheckbox("CMS_VAR[29]", "yes");
$checkbox->setChecked( "CMS_VALUE[29]" );
$td->setContent($checkbox);
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

//Sorting
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_sort"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$input = new cHTMLRadiobutton("CMS_VAR[16]", 'ASC', '', $sort_asc);
$input->setLabelText(mi18n("value_asc"));
$td->setContent($input);
$input2 = new cHTMLRadiobutton("CMS_VAR[16]", 'DESC', '', $sort_desc);
$input2->setLabelText(mi18n("value_desc"));
$td->setContent($input."  ".$input2);
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

/*

//Weekday Display Handling
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_showweekdays"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$input = new cHTMLRadiobutton("CMS_VAR[7]", 'kurz', '', $weekdays_short);
$input->setLabelText(mi18n("weekdays_short"));
$td->setContent($input);
$input2 = new cHTMLRadiobutton("CMS_VAR[7]", 'lang', '', $weekdays_long);
$input2->setLabelText(mi18n("weekdays_long"));
$input3 = new cHTMLRadiobutton("CMS_VAR[7]", 'keine', '', $weekdays_none);
$input3->setLabelText(mi18n("weekdays_none"));
$td->setContent($input."  ".$input2."  ".$input3);
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

//Month Display Handling
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_showmonth"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$checkbox = new cHTMLCheckbox("CMS_VAR[12]", "");
$checkbox->setChecked( "CMS_VALUE[12]" );
$td->setContent($checkbox);
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

//Same day group Handling
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_groupsameday"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$checkbox = new cHTMLCheckbox("CMS_VAR[8]", "");
$checkbox->setChecked( "CMS_VALUE[8]" );
$td->setContent($checkbox);
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

//Linked Dates Handling
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_linkeddates"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$checkbox = new cHTMLCheckbox("CMS_VAR[9]", "");
$checkbox->setChecked( "CMS_VALUE[9]" );
$td->setContent($checkbox);
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

//None-HTML Content Display Handling
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_displaycontent"));
$td->setClass("silverline");
$fillrow[] = $td;
$td = new cHTMLTableData();
$checkbox = new cHTMLCheckbox("CMS_VAR[10]", "");
$checkbox->setChecked( "CMS_VALUE[10]" );
$td->setContent($checkbox);
$td->setClass("silverline");
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

*/

// echo this fieldset, add legend and JS-functionality first
$table->setContent($filltable);

$fieldset = new cHTMLFieldset();
$fieldset->appendContent('<legend class="'.$faqclass.'"><h4><span class="morebutton '.$faqclass.'">&raquo;</span>'.mi18n("module configuration").'</h4></legend>');
$fieldset->appendContent('<div class="answer">');
$fieldset->appendContent($table);
$fieldset->appendContent('</div>');
echo $fieldset->render();

//********************************
// start all over with fresh table
//********************************

$filltable = array();
$table = new cHTMLTable();

//first row with putting module on-/offline
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_timeframeoptions"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$checkbox = new cHTMLCheckbox("CMS_VAR[23]", "yes");
$checkbox->setLabelText("" );
$checkbox->setChecked( "CMS_VALUE[23]" );
$td->setContent($checkbox);
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

//start next row with choice of theme categories' display options
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_showcat"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$input = new cHTMLRadiobutton("CMS_VAR[22]", 'none', '', $show_cat_none);
$input->setLabelText(mi18n("value_none"));
$td->setContent($input);
$input2 = new cHTMLRadiobutton("CMS_VAR[22]", 'checkbox', '', $show_cat_checkbox);
$input2->setLabelText(mi18n("value_checkbox"));
$td->setContent($input."  ".$input2);
$input3 = new cHTMLRadiobutton("CMS_VAR[22]", 'dropdown', '', $show_cat_dropdown);
$input3->setLabelText(mi18n("value_dropdown"));
$td->setContent($input."  ".$input2."  ".$input3);
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

// echo this fieldset, add legend and JS-functionality first
$table->setContent($filltable);

$fieldset = new cHTMLFieldset();
$fieldset->appendContent('<legend class="'.$faqclass.'"><h4><span class="morebutton '.$faqclass.'">&raquo;</span>'.mi18n("feu configuration").'</h4></legend>');
$fieldset->appendContent('<div class="answer">');
$fieldset->appendContent($table);
$fieldset->appendContent('</div>');
echo $fieldset->render();

//********************************
// start all over with calendar table
//********************************

$filltable = array();
$table = new cHTMLTable();

//first row with calendar dropdown options
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_caldropdown"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$checkbox = new cHTMLCheckbox("CMS_VAR[26]", "yes");
$checkbox->setLabelText("" );
$checkbox->setChecked( "CMS_VALUE[26]" );
$td->setContent($checkbox);
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

//start next row with calendar year options
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_calendar_years"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$input = new cHTMLTextbox("CMS_VAR[25]", "CMS_VALUE[25]",'15','30','max_articleamount',false,null,'');
$td->setContent($input);
//$td->setClass("silverline");
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

// echo this fieldset, add legend and JS-functionality first
$table->setContent($filltable);

$fieldset = new cHTMLFieldset();
$fieldset->appendContent('<legend class="'.$faqclass.'"><h4><span class="morebutton '.$faqclass.'">&raquo;</span>'.mi18n("cal configuration").'</h4></legend>');
$fieldset->appendContent('<div class="answer">');
$fieldset->appendContent($table);
$fieldset->appendContent('</div>');
echo $fieldset->render();
//********************************
// start all over with fresh table
//********************************

$filltable = array();
$table = new cHTMLTable();

//start next row with additional category choices


$i = 50; $content = "";
// show available categories
	$fillrow   = array();
	$tr = new cHTMLTableRow();
	$td = new cHTMLTableData();
	$td->setContent(mi18n("label_categories"));
	$fillrow[] = $td;
	$td = new cHTMLTableData();
foreach ( $MOD[categories] AS $category)
    {

	$checkbox = new cHTMLCheckbox( "CMS_VAR[$i]", $category );
	$checkbox->setLabelText( $category );
	$checkbox->setChecked( "CMS_VALUE[$i]" );
	$content .= $checkbox;
	$i++;
    }

	$td->setContent($content);
	$fillrow[] = $td;
	$tr->setContent($fillrow);
	$filltable[] = $tr;


// final row with submit button only
$fillrow = array();
$tr = new cHTMLTableRow();
$tr->setClass("darkgray");
$td = new cHTMLTableData();
$td->setContent("");
$fillrow[] = $td;
$td = new cHTMLTableData();
$td->setContent($sSubmitLink);
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;


// echo this fieldset, add legend and JS-functionality first
$table->setContent($filltable);

$fieldset = new cHTMLFieldset();
$fieldset->appendContent('<legend class="'.$faqclass.'"><h4><span class="morebutton '.$faqclass.'">&raquo;</span>'.mi18n("category configuration").'</h4></legend>');
$fieldset->appendContent('<div class="answer">');
$fieldset->appendContent($table);
$fieldset->appendContent('</div>');
echo $fieldset->render();



?><?php