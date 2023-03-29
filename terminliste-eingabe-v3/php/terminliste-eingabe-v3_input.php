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
* Modulname      :     Termineingabe/anzeige universal
* Version        :     3.0
* Author         :     schlaucher (Original 4.8 version)
* 4.9 Adaptation :     Viktor Lehmann, TONE2TONE
* Created        :     12-07-2006 (schlaucher)
* Modified       :     20.08.2015 (T2T)
************************************************/ 

// Includes
if ( !function_exists("getChildPicas") ) { cInclude("module", "js.input.alr.php"); }

// Initialization
$bDebug          = false;
$sSubmitLink     = '<a href="javascript: if (document.tplcfgform.send) {document.tplcfgform.send.value = 0}; document.tplcfgform.submit();"><img class="submitbutton" src="images/submit.gif" title="'.mi18n("Save changes").'" /></a>';

// Start der Output-Ausgabe
$db = cRegistry::getDb();
$cfg = cRegistry::getConfig();
$client = cRegistry::getClientId();
$cfgClient = cRegistry::getClientConfig();

$MOD_TE_img_show       = ( "CMS_VALUE[11]" == "" ) ? "checked" : ""; 
$MOD_TE_imgw           = ( "CMS_VALUE[12]" == "" ) ? "100" : "CMS_VALUE[12]"; 
$MOD_TE_imgh           = ( "CMS_VALUE[13]" == "" ) ? "100" : "CMS_VALUE[13]";
$MOD_TE_imgar          = ( "CMS_VALUE[14]" == "" ) ? "keine" : "CMS_VALUE[14]";
$MOD_TE_imgva          = ( "CMS_VALUE[15]" == "" ) ? "0" : "CMS_VALUE[15]";
$MOD_TE_imgha          = ( "CMS_VALUE[16]" == "" ) ? "0" : "CMS_VALUE[16]";
$MOD_TE_ortsliste      = ( "CMS_VALUE[17]" == "" ) ? mi18n("freier Eintrag") : "CMS_VALUE[17]";

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
//$fillrow[] = $td;
//$td = new cHTMLTableData();
$td->setColspan("2");
$input = new cHTMLTextbox("CMS_VAR[17]", "CMS_VALUE[17]",'30','100','max_articleamount',false,null,'');
$td->setContent(mi18n("label_citylist")."<br>".$input."<br>".mi18n("info_cityhandling"));
$td->setClass("silverline");
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;

/*
//start next row with choice of show/hide setting boxes
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("info_categorynames"));
$fillrow[] = $td;
$tr->setContent($fillrow);
$filltable[] = $tr;



//CatA: Show 10 Categories

for($i = 1; $i <=10; $i++)
    {
		$fillrow = array();
		$tr = new cHTMLTableRow();
		$td = new cHTMLTableData();
		$input = new cHTMLTextbox("CMS_VAR[$i]", "CMS_VALUE[$i]",'30','100','cat'.$i,false,null,'');
		$td->setContent(mi18n("category")." A".$i." ".$input);
		$fillrow[] = $td;
		$tr->setContent($fillrow);
		$filltable[] = $tr;

    }
*/

//start next row with Editor-Template info
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_template_editor"));
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

//start next row with FE-Display-Template info
$fillrow = array();
$tr = new cHTMLTableRow();
$td = new cHTMLTableData();
$td->setContent(mi18n("label_template_fedisplay"));
$fillrow[] = $td;
$td = new cHTMLTableData();
$select = new cHTMLSelectElement("CMS_VAR[25]");

$option = new cHTMLOptionElement(mi18n("please_choose"), "");
$select->appendOptionElement($option);
foreach ($tplFiles AS $key=>$value) {
	$value = str_replace($tplpath, "", $value);

	$option = new cHTMLOptionElement($value, $value);
    if ("CMS_VALUE[25]" == $value) {
        $option->setSelected(true);
    }
    $select->appendOptionElement($option);
}

$td->setContent($select);
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


// echo this table
$table->setContent($filltable);
echo $table->render();



?><?php