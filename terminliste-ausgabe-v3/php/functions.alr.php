<?php 

function text2adapt($text, $striptags, $applyfurtherchanges) {

	// first value = string to adapt; second value (true/false) whether strip_tags should be executed; third value (true/false) whether an additional routine should be applied, e.g. codepage changes (to be defined by Programmer)
	// um die Funktion "Eigene Textänderungsroutine anwenden" aus dem Inputbereich auszuführen, hier bitte den Code in if ($applychanges) anpassen
	
	if ( $striptags ) $text = strip_tags($text) ;
	if ( $applyfurtherchanges ) {
		// Programmers, define your own routine, here is an example:
		$text = html_entity_decode($text);
		//$text = urldecode($text) ;
	}
	return $text; 
}

//  function to include child tagging categories

function getChildPicas($parent) {
global $cfg, $lang;

$db4 = cRegistry::getDb();
$sql4 = "SELECT LANG.idpica_alloc as id FROM ".$cfg["tab"]["pica_alloc"]." AS ALLOC JOIN ".$cfg["tab"]["pica_lang"]." AS LANG ON ALLOC.idpica_alloc=LANG.idpica_alloc WHERE ALLOC.parentid=$parent AND LANG.online=1";
$db4->query($sql4);
if ($db4->numRows() > 0) {
    while ($db4->nextRecord()) {
	$picas .= ",".$db4->f("id").getChildPicas($db4->f("id"));
    }
}
return $picas;
}

?>