<?php

/***********************************************
 * CONTENIDO MODUL - OUTPUT
 *
 * Modulname      :     Terminliste Ausgabe
 * Version        :     3.0
 * Author         :     schlaucher (Original 4.8 version)
 * 4.9 Adaptation :     Viktor Lehmann, TONE2TONE
 * Created        :     12-07-2006 (schlaucher)
 * Modified       :     20.08.2015 (T2T)
 ************************************************/

/**
 * Input fields, as determined in Module Terminliste Eingabe
 *
 * $MOD_TE_termin[0]  = Startdatum // not used anymore
 * $MOD_TE_termin[1]  = Starttermin: holds date AND time through CMS_DATE[1]
 * $MOD_TE_termin[2]  = Endtermin: holds date AND time through CMS_DATE[2]
 * $MOD_TE_termin[3]  = Endzeit // not used anymore
 * $MOD_TE_termin[4]  = Ort
 * $MOD_TE_termin[5]  = Details
 * $MOD_TE_termin[6]  = Titel
 * $MOD_TE-termin[7]  = Check für Teaser
 * $MOD_TE_termin[8]  = Bemerkung CMS_HTML[8]
 * $MOD_TE_termin[9]  = Link CMS_LINK[9]
 * $MOD_TE_termin[10] = Termin Zyklus
 * $MOD_TE_termin[11] = Termin Zyklus Wochentage
 * $MOD_TE_termin[12] = jeden 1.,2.,3.,4.,5. Wochentag
 * $MOD_TE_termin[13] = Themenkategorien
 * $MOD_TE_termin[14] = Image
 * $MOD_TE_termin[15] = Status
 * $MOD_TE_termin[16] = Termin Zyklus jeden x-ten Tag
 * $MOD_TE_termin[17] = Termin Zyklus Ausschlussliste
 * $MOD_TE_termin[18] = Termin Zyklus Anzeigemodus1 (alle/aktuelle)
 * $MOD_TE_termin[19] = Termin Zyklus Anzeigemodus2(Anzahl)
 * $MOD_TE_termin[20] = Termin markieren durch class-Eintrag
 * $MOD_TE_termin[21] = Output: according to module config, feu-form oder calendar?
 * $MOD_TE_termin[22] = Option für Ausgabe der Themenkategorien im FEU-Form: Keine, Dropdown oder Checkbuttons?
 */

global $cfg, $client, $db, $errors, $idart, $lang;

global $REQUEST_URI;

global $MOD_TL_durchlaufdcat;
global $MOD_TS_datum_bis, $MOD_TS_datum_von, $MOD_TS_timeopt;

global $feuselectedcat;

// Includes
cInclude("module", "functions.terminliste.php");
cInclude("frontend", "includes/functions.t2t.php");

$bDebug      = false;
$MOD         = [];
$terminliste = [];

// needs date settings in administration / languages (last 4 input elements per language menu)
$oLang = new cApiLanguage();
$oLang->loadByPrimaryKey($lang);

$MOD["datetimeformat"] = $oLang->getProperty("dateformat", "full");
$MOD["dateformat"]     = $oLang->getProperty("dateformat", "date");
$MOD["timeformat"]     = $oLang->getProperty("dateformat", "time");

// add genericDate so that even dates with no date categorie can be tracked
$clientcategories[1] = "genericDate;" . getEffectiveSetting('terminliste', 'categories', '');
$MOD["categories"]   = explode(";", $clientcategories[1]);
if ($bDebug) {
    print "<br>Mandanteneinstellungen für Kategorien:<br><pre>";
    print_r($MOD["categories"]);
    print "</pre>";
}
$catcount = count(explode(";", $clientcategories[1]));

// Create language dependent variables to replace date and time with locales
// the array number represents the desired frontend language number. Add more array values for other languages
// examples here: 1 - DE, 2 - US

$days_short[1]   = [
    0 => 'So',
    1 => 'Mo',
    2 => 'Di',
    3 => 'Mi',
    4 => 'Do',
    5 => 'Fr',
    6 => 'Sa',
];
$days_full[1]    = [
    0 => 'Sonntag',
    1 => 'Montag',
    2 => 'Dienstag',
    3 => 'Mittwoch',
    4 => 'Donnerstag',
    5 => 'Freitag',
    6 => 'Samstag',
];
$months_short[1] = [
    1  => 'Jan',
    2  => 'Feb',
    3  => 'Mär',
    4  => 'Apr',
    5  => 'Mai',
    6  => 'Jun',
    7  => 'Jul',
    8  => 'Aug',
    9  => 'Sep',
    10 => 'Okt',
    11 => 'Nov',
    12 => 'Dez',
];
$months_full[1]  = [
    1  => 'Januar',
    2  => 'Februar',
    3  => 'März',
    4  => 'April',
    5  => 'Mai',
    6  => 'Juni',
    7  => 'Juli',
    8  => 'August',
    9  => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Dezember',
];

// Examples for other language versions
// $days_short[2]   = [
//     0 => 'Sun',
//     1 => 'Mon',
//     2 => 'Tue',
//     3 => 'Wed',
//     4 => 'Thu',
//     5 => 'Fri',
//     6 => 'Sat',
// ];
// $days_full[2]    = [
//     0 => 'Sunday',
//     1 => 'Monday',
//     2 => 'Tuesday',
//     3 => 'Wednesday',
//     4 => 'Thursday',
//     5 => 'Friday',
//     6 => 'Saturday',
// ];
// $months_short[2] = [
//     1  => 'Jan',
//     2  => 'Feb',
//     3  => 'Mar',
//     4  => 'Apr',
//     5  => 'May',
//     6  => 'Jun',
//     7  => 'Jul',
//     8  => 'Aug',
//     9  => 'Sep',
//     10 => 'Oct',
//     11 => 'Nov',
//     12 => 'Dec',
// ];
// $months_full[2]  = [
//     1  => 'January',
//     2  => 'February',
//     3  => 'March',
//     4  => 'April',
//     5  => 'May',
//     6  => 'June',
//     7  => 'July',
//     8  => 'August',
//     9  => 'September',
//     10 => 'October',
//     11 => 'November',
//     12 => 'December',
// ];

// definitions of variables
$MOD["datensaetze"] = 0; // Zähler für das Auslesen der Datensätze
$MOD["zaehler"]     = 0; // Zähler für den Termindurchlauf
$MOD["zaehler2"]    = 0; // Zähler für zyklische Termine
$MOD["gefunden"]    = false; // für die Steuerung der Anzeige (Termine gefunden)

// Variablen aus Input Bereich
$MOD["display"]      = "CMS_VALUE[0]" === 'true'; // show list at all. To be programmed in Smarty template.
$MOD["tpl_results"]  = "CMS_VALUE[24]" != "" ? "CMS_VALUE[24]" : "results_success.tpl";
$MOD["tpl_calendar"] = "CMS_VALUE[27]" != "" ? "CMS_VALUE[27]" : "calendar.tpl";
$MOD["tpl_feuform"]  = "CMS_VALUE[28]" != "" ? "CMS_VALUE[28]" : "feu_searchform.tpl";
$MOD["alle"]         = "CMS_VALUE[1]" == '' ? "no" : "yes"; // abgelaufene Termine anzeigen
$MOD["group"]        = "CMS_VALUE[8]" == '' ? "no" : "yes"; // Termine gruppieren
$MOD["linkyn"]       = "CMS_VALUE[9]" == '' ? "no" : "yes"; // Termine verlinken
$MOD["linkyn"]       = "yes"; // Termine verlinken
$MOD["htmlyn"]       = ("CMS_VALUE[10]" == '') ? "no" : "yes"; // Anzeige des Termins auch ohne HTML Info
$MOD["show_monat"]   = !("CMS_VALUE[12]" == '');
$MOD["kata_tcheck"]  = !("CMS_VALUE[19]" == '');

$MOD["zeitraum1"]      = "CMS_VALUE[2]"; // Zeitraum für abgelaufene Termine
$MOD["zeitraum2"]      = "CMS_VALUE[3]"; // Zeitraum für aktuelle Termine
$MOD["last_days"]      = "CMS_VALUE[4]"; // abgelaufene Termine der letzten x Tage
$MOD["von_datum"]      = "CMS_VALUE[5]"; // Datum für aktuelle Termine; Anzeige von
$MOD["bis_datum"]      = "CMS_VALUE[6]"; // Datum für aktuelle Termine; Anzeige bis
$MOD["wtag_bez"]       = "CMS_VALUE[7]"; // Anzeige der Wochentage
$MOD["idcat"]          = "CMS_VALUE[11]"; // Kategorie der Termine
$MOD["plustag"]        = "CMS_VALUE[13]"; // Bei Wochen- und Monatsansicht zusätzlich x Tage anzeigen
$MOD["z2_tage"]        = "CMS_VALUE[14]"; // Termine der nächsten x Tage anzeigen
$MOD["anzahl_termine"] = "CMS_VALUE[15]"; // x Termine anzeigen
$MOD["sort"]           = "CMS_VALUE[16]"; // Sortierung der Termine
$MOD["add_tree"]       = "CMS_VALUE[17]"; // zusätzliche Kategorien anzeigen
$MOD["add_tree_ids"]   = "CMS_VALUE[18]"; // ids der zusätzlichen Kategorien
$MOD["modconfigbased"] = "CMS_VALUE[21]";
$MOD["show_cat"]       = !("CMS_VALUE[22]" == "none"); // Anzeige der Kategorien
$MOD["catform"]        = "CMS_VALUE[22]"; // Dropdown oder Checkboxen
$MOD["show_timeopt"]   = "CMS_VALUE[23]";
$MOD["show_teaser"]    = !("CMS_VALUE[29]" == '');

// calendar specific
$MOD["anzeigemodus"] = true; // Anzeigemodus Datum oder Status// Überprüfung ob zusätzliche Kategorien gewählt wurden
$MOD["ddownyn"]      = "CMS_VALUE[26]" == "yes" && "CMS_VALUE[25]" != "";  // Dropdown-Feld aktiv
$MOD["array_down"]   = "CMS_VALUE[25]" != "" ? explode(";", "CMS_VALUE[25]") : ""; // Angezeigte Jahre im Dropdown Feld
$MOD["idart"]        = $idart;

// has FEU-Form been used? If so, this one has priority over module configuration settings
$MOD["timeopt_none"]  = $MOD_TS_timeopt == "keine" ? "checked" : "";
$MOD["timeopt_week"]  = $MOD_TS_timeopt == "woche" ? "checked" : "";
$MOD["timeopt_month"] = $MOD_TS_timeopt == "monat" ? "checked" : "";
$MOD["timeopt_year"]  = $MOD_TS_timeopt == "jahr" ? "checked" : "";

// to do: do an error check on the FEU-Input (dates mainly) and program an error routine, style:
// $errors[] = mi18n("error_datenotvalid");

// Ermittlung des Anzeigezeitraumes

// from calendar 
$calendarrequest = false;
if ($MOD["modconfigbased"] == "calendar") {
    // Datum wurde über den sKalender neu gesetzt
    if ($_REQUEST['MOD_TL_newdate']) {
        $MOD["newdate_array"] = explode("-", $_REQUEST['MOD_TL_newdate']);  // yyyy-m-t oder mm tt
        $MOD["checkdate_von"] = date("Y-m-d", mktime(0, 0, 0, $MOD["newdate_array"][1], 1, $MOD["newdate_array"][0]));
        $MOD["tag_max"]       = date("t", mktime(0, 0, 0, $MOD["newdate_array"][1], 1, $MOD["newdate_array"][0]));
        $MOD["checkdate_bis"] =
            date("Y-m-d", mktime(0, 0, 0, $MOD["newdate_array"][1], $MOD["tag_max"], $MOD["newdate_array"][0]));
    }

    // Datum wurde über die Auswahl eines Termins im sKalender neu gesetzt
    if ($_REQUEST['MOD_TL_newdate'] && ($_REQUEST['MOD_TL_show'] == 't')) {
        $MOD["newdate_array"] = explode("-", $_REQUEST['MOD_TL_newdate']);  // yyyy-m-t oder mm tt
        $MOD["checkdate_von"] = date(
            "Y-m-d",
            mktime(0, 0, 0, $MOD["newdate_array"][1], $MOD["newdate_array"][2], $MOD["newdate_array"][0])
        );
        $MOD["checkdate_bis"] = date(
            "Y-m-d",
            mktime(0, 0, 0, $MOD["newdate_array"][1], $MOD["newdate_array"][2], $MOD["newdate_array"][0])
        );
        $calendarrequest      = true;
    }

    // aktuelles Datum verwenden
    if (!isset($_REQUEST['MOD_TL_newdate'])) {
        $MOD["heute"]         = getdate();
        $MOD["checkdate_von"] = date("Y-m-d", mktime(0, 0, 0, $MOD["heute"]["mon"], 1, $MOD["heute"]["year"]));
        $MOD["tag_max"]       = date("t", mktime(0, 0, 0, $MOD["heute"]["mon"], 1, $MOD["heute"]["year"]));
        $MOD["checkdate_bis"] =
            date("Y-m-d", mktime(0, 0, 0, $MOD["heute"]["mon"], $MOD["tag_max"], $MOD["heute"]["year"]));
    }
}

// from Module Configuration

if ($MOD["modconfigbased"] == "module") {
    // Anzeigedatum des ältesten Termins
    $MOD["checkdate_von"] = dateout($MOD["zeitraum1"], $MOD["last_days"]);

    // wenn von - bis gewählt, werden abgelaufene Termine nicht angezeigt
    // Anzeigedatum des aktuellsten Termins
    $MOD["checkdate_bis"] = datein($MOD["zeitraum2"], $MOD["plustag"], $MOD["z2_tage"], $MOD["bis_datum"]);
    if ($MOD["zeitraum2"] == 'datum') {
        $MOD["checkdate_von"] = $MOD["von_datum"] != "" ? $MOD["von_datum"] : "1970-01-01";
        $MOD["checkdate_bis"] = $MOD["bis_datum"] != "" ? $MOD["bis_datum"] : "9999-99-99";
    }
}

// from feuform, if submitted
$feurequest = isset($_POST['MOD_TS_datum_von']);
if ($MOD["modconfigbased"] == "feuform") {
    if ($_POST['MOD_TS_datum_von'] != '') {
        $dateHelper           = new DateTime($_POST['MOD_TS_datum_von']);
        $MOD["checkdate_von"] = $dateHelper->format("Y-m-d");
    } else {
        $MOD["checkdate_von"] = '9999-99-99';
    }

    if ($_POST['MOD_TS_datum_bis'] != '') {
        $dateHelper           = new DateTime($_POST['MOD_TS_datum_bis']);
        $MOD["checkdate_bis"] = $dateHelper->format("Y-m-d");
    } else {
        $MOD["checkdate_bis"] = '9999-99-99';
    }

    if (!$feurequest) {
        $MOD["checkdate_von"] = '9999-99-99';
        $MOD["checkdate_bis"] = '9999-99-99';
    }

    // check time options
    if ($_POST['MOD_TS_timeopt']) {
        $MOD["checkdate_von"] = dateout($_POST['MOD_TS_timeopt'], '');
        $MOD["checkdate_bis"] = datein($_POST['MOD_TS_timeopt'], '', '', '');
    }
}

if ($bDebug) {
    print "<br>Datumswahl eingestellt auf von " . $MOD["checkdate_von"] . " bis " . $MOD["checkdate_bis"];
}

// NEW: DETECT CATEGORIES TO BROWSE WHEN CHECKBUTTON; MIGHT BE SAME FOR DROPDOWNS

// module configuration settings
// this one is also used to determine the theme categories to be displayed in the FEU-form
$selectedcat = "";
for ($i = 50; $i <= 50 + count($MOD["categories"]); $i++) {
    if ("CMS_VALUE[$i]" != "") {
        $selectedcat .= "CMS_VALUE[$i]" . ";";
    }
}
if ($selectedcat != "") {
    $selectedcat       = substr($selectedcat, 0, -1);
    $referencedTplCats = explode(";", $selectedcat);
} else {
    //$referencedTplCats = $MOD["categories"];
    $referencedTplCats = explode(";", "genericDate");
}
$MOD["categories_sel"][1] = $selectedcat;

// FEU selection from FEU form
$selectedcat = "";

if ($MOD["modconfigbased"] == "feuform") {
    if ($_POST['MOD_TS_cat_check'] == "yes") {
        foreach ($_POST as $key => $value) {
            if (substr($key, 0, 10) == 'categories') {
                $selectedcat .= $value . ";";
            }
        }
        if ($selectedcat != "") {
            $selectedcat = substr($selectedcat, 0, -1);
        }
        if ($bDebug) {
            print "<br>FEU-Kategorien vor Explode<br>: " . $selectedcat;
        }

        $referencedFEUCats = explode(";", $selectedcat);
    } else {
        //$referencedFEUCats = ( count($referencedTplCats) > 0 ) ? $referencedTplCats : $MOD["categories"];
        $referencedFEUCats = (count($referencedTplCats) > 0) ? $referencedTplCats : explode(";", "genericDate");
    }
    if ($bDebug) {
        print "<br>FEU-Kategorien NACH Explode:<br><pre>";
        print_r($referencedFEUCats);
        print "</pre>";
    }
}

// now decide which category comparison to choose from. FEU requests first over module configuration
$selectedcategories = $feurequest ? $referencedFEUCats : $referencedTplCats;
// in case the FEU form shows no categories and none are set in module configuration: choose all available ones nonetheless
if (!$MOD["show_cat"] && $feurequest && count($referencedTplCats) == 0) {
    $selectedcategories = $MOD["categories"];
}
//$selectedcategories = $MOD["categories"] ;

if ($bDebug) {
    print "<br>Ausgabe der modulgesteuerten Kategorien:<br><pre>";
    print_r($referencedTplCats);
    print "</pre>";

    print "<br>Ausgabe der FEU-gesteuerten Kategorien:<br><pre>";
    print_r($referencedFEUCats);
    print "</pre>";

    print "<br>Ausgabe der am Ende zugewiesenen Kategorien:<br><pre>";
    print_r($selectedcategories);
    print "</pre>";
}

// Überprüfung ob zusätzliche Verzeichnis-Kategorien gewählt wurden
if ($MOD["add_tree"] == "" || $MOD["add_tree_ids"] == "" && $MOD["add_tree"] == "auswahl") {
    $MOD["add_tree"] = 'keine';
}

// zusätzliche Verzeichnis-Kategorien ermitteln
$MOD["sel_tree"] = "'" . $MOD["idcat"] . "'";
switch ($MOD["add_tree"]) {
    case "below":
        $MOD_TL_tree_level = -1;

        $sql_tree = "SELECT CAT.idcat AS idcat, CATTREE.level AS level ";
        $sql_tree .= "FROM " . $cfg["tab"]["cat"] . " AS CAT,";
        $sql_tree .= " " . $cfg["tab"]["cat_tree"] . " AS CATTREE ";
        $sql_tree .= "WHERE CAT.idcat = CATTREE.idcat ";
        $sql_tree .= "ORDER BY CATTREE.idtree";

        $db->query($sql_tree);
        while ($db->nextRecord()) {
            if ($db->f("idcat") == $MOD["idcat"]) {
                $MOD_TL_tree_level = $db->f("level");
            } elseif ($MOD_TL_tree_level > -1 && $db->f("level") > $MOD_TL_tree_level) {
                $MOD["sel_tree"] .= ",'" . $db->f("idcat") . "'";
            } elseif ($db->f("level") <= $MOD_TL_tree_level) {
                break;
            }
        }
        break;
    case "select":
        $MOD["add_tree_array"] = explode(",", $MOD["add_tree_ids"]);
        foreach ($MOD["add_tree_array"] as $value) {
            $addicdat        = str_replace("cat_", "", $value);
            $MOD["sel_tree"] .= ",'" . $addicdat . "'";
        }
        break;
    default:
}

// Alle Artikel der Kategorie einlesen, die nicht Startartikel sind
$sql = "SELECT  ARTLANG.idart, CONTENT.value, CONTENT.typeid, ARTLANG.idartlang, CONTENT.idtype ";
$sql .= "FROM " . $cfg["tab"]["cat_art"] . " AS CATART,";
$sql .= " " . $cfg["tab"]["art_lang"] . " AS ARTLANG,";
$sql .= " " . $cfg["tab"]["cat"] . " AS CAT,";
$sql .= " " . $cfg["tab"]["content"] . " AS CONTENT,";
$sql .= " " . $cfg["tab"]["cat_lang"] . " AS CATLANG ";
$sql .= "WHERE ARTLANG.idart = CATART.idart ";
$sql .= "AND CATART.idcat = CAT.idcat ";
$sql .= "AND ARTLANG.idartlang = CONTENT.idartlang ";
$sql .= "AND ARTLANG.idlang = '" . $lang . "' ";
$sql .= "AND CAT.idcat IN (" . $MOD["sel_tree"] . ") ";
$sql .= "AND CAT.idclient = '" . $client . "' ";
$sql .= "AND ARTLANG.online = '1' "; // nur Artikel die online sind
$sql .= "AND CATART.idcat = CATLANG.idcat ";
if ($cfg["is_start_compatible"]) {
    $sql .= "AND CATART.is_start = '0' ";
} else {
    $sql .= "AND CONTENT.idartlang != CATLANG.startidartlang ";
}
$sql .= "ORDER BY ARTLANG.idart, CONTENT.typeid ASC";

$db->query($sql);

// weiter, wenn Artikel gefunden wurden werden die Felder in ein Array geschrieben
if ($db->numRows() > 0) {
    // Array erzeugen, in dem die Termindaten abgelegt werden;
    $MOD["termine"] = [];

    // alle Datensätze durchlaufen und Inhalte in Array schreiben
    while ($MOD["datensaetze"] < $db->numRows()) {
        $db->nextRecord();
        if ($db->f("idart") > $MOD["termine"][$MOD["zaehler"]]["idart"]) {
            $MOD["zaehler"]++;
        }
        if (!isStartArticle($db->f("idartlang"), $MOD_TL_durchlaufdcat, $lang)) {
            $element_type = $db->f("idtype");

            switch ($db->f("typeid")) {
                // Anfangstermin inkl Uhrzeit
                case 1:
                    // Datum
                    if ($element_type == '19') {
                        $dom = new domDocument;
                        $dom->loadXML($db->f('value'));
                        $xml      = simplexml_import_dom($dom);
                        $date_Ymd = date('Y-m-d', (int)$xml->timestamp);
                        $date_His = date('H:i:s', (int)$xml->timestamp);

                        $MOD["termine"][$MOD["zaehler"]]["datum1"]       =
                            ($date_Ymd == "0000-00-00") ? "1970-01-01" : $date_Ymd;
                        $MOD["termine"][$MOD["zaehler"]]["zeit1"]        = $date_His;
                        $MOD["termine"][$MOD["zaehler"]]["idart"]        = $db->f("idart");
                        $MOD["termine"][$MOD["zaehler"]]["text"]         = '';
                        $MOD["termine"][$MOD["zaehler"]]["linktype"]     = '';
                        $MOD["termine"][$MOD["zaehler"]]["linkexternal"] = '';
                        $MOD["termine"][$MOD["zaehler"]]["linkinternal"] = '';
                        $MOD["termine"][$MOD["zaehler"]]["linkfilename"] = '';
                        $MOD["termine"][$MOD["zaehler"]]["ortdetail"]    = '';
                        $MOD["termine"][$MOD["zaehler"]]["linkdesc"]     = '';
                        $MOD["termine"][$MOD["zaehler"]]["image"]        = '';
                        $MOD["termine"][$MOD["zaehler"]]["linkframe"]    = '';
                        $MOD["termine"][$MOD["zaehler"]]["kategorie"]    = '';
                        $MOD["termine"][$MOD["zaehler"]]["zyklus"]       = 'no';
                        $MOD["termine"][$MOD["zaehler"]]["xtag"]         = '';
                        $MOD["termine"][$MOD["zaehler"]]["aliste"]       = '';
                        $MOD["termine"][$MOD["zaehler"]]["highlight"]    = '';
                        $MOD["termine"][$MOD["zaehler"]]["zutermine"]    = '';
                        //precaution: set date2=date1, might be overwritten later
                        $MOD["termine"][$MOD["zaehler"]]["datum2"] = $MOD["termine"][$MOD["zaehler"]]["datum1"];
                        $MOD["termine"][$MOD["zaehler"]]["zeit2"]  = $MOD["termine"][$MOD["zaehler"]]["zeit1"];
                    }
                    break;

                // Endtermin inkl. Uhrzeit
                case 2:
                    if ($element_type == '19') {
                        $dom = new domDocument;
                        $dom->loadXML($db->f('value'));
                        $xml                                       = simplexml_import_dom($dom);
                        $date_Ymd                                  = date('Y-m-d', (int)$xml->timestamp);
                        $date_His                                  = date('H:i:s', (int)$xml->timestamp);
                        $MOD["termine"][$MOD["zaehler"]]["datum2"] = $date_Ymd;
                        $MOD["termine"][$MOD["zaehler"]]["zeit2"]  = $date_His;
                    }
                    break;

                // Ort
                case 4:
                    // Text
                    if ($element_type == '3') {
                        $MOD["termine"][$MOD["zaehler"]]["ort"] = $db->f("value");
                    }
                    break;

                // Ortdetail
                case 5:
                    // Text
                    if ($element_type == '3') {
                        $MOD["termine"][$MOD["zaehler"]]["ortdetail"] = $db->f("value");
                    }
                    break;

                // Titel
                case 6:
                    // Text
                    if ($element_type == '3') {
                        $MOD["termine"][$MOD["zaehler"]]["titel"] = $db->f("value");
                    }
                    break;

                // Teaser
                case 7:
                    // Text
                    if ($element_type == '3') {
                        $MOD["termine"][$MOD["zaehler"]]["teaser"] = ($db->f("value") == "yes") ? true : false;
                    }
                    break;

                // HTML
                case 8:
                    $MOD["termine"][$MOD["zaehler"]]["text"] = $db->f("value");
                    break;

                // Link, Achtung 4 Zustände
                case 9:
                    $dom = new domDocument;
                    $dom->loadXML($db->f("value"));
                    $xml                                             = simplexml_import_dom($dom);
                    $MOD["termine"][$MOD["zaehler"]]["linktype"]     = $xml->type;
                    $MOD["termine"][$MOD["zaehler"]]["linkexternal"] = $xml->externallink;
                    $MOD["termine"][$MOD["zaehler"]]["linkframe"]    = $xml->newwindow;
                    $MOD["termine"][$MOD["zaehler"]]["linkdesc"]     = $xml->title;
                    $MOD["termine"][$MOD["zaehler"]]["linkinternal"] = $xml->idart;
                    $MOD["termine"][$MOD["zaehler"]]["linkfilename"] = $xml->filename;
                    break;

                // Termin Zyklus
                case 10:
                    $MOD["termine"][$MOD["zaehler"]]["zyklus"] =
                        ($element_type == '3' && $db->f("value") != "") ? $db->f("value") : "no";
                    break;

                // Zyklus Wochentage
                case 11:
                    // Text
                    if ($element_type == '3') {
                        $MOD["termine"][$MOD["zaehler"]]["wtagezyklus"] = $db->f("value");
                    }
                    break;

                // 1., 2., ... Wochentag im Monat
                case 12:
                    // Text
                    if ($element_type == '3') {
                        $MOD["termine"][$MOD["zaehler"]]["wtagemon"] = $db->f("value");
                    }
                    break;

                // Terminkategorien
                case 13:
                    // Text
                    if ($element_type == '3') {
                        $MOD["termine"][$MOD["zaehler"]]["kategorie"] =
                            $db->f("value") == "" ? "genericDate" : "genericDate;" . $db->f("value");
                    }

                // Bild
                case 14:
                    if ($element_type == '22') {
                        $imageid = $db->f("value");
                        $tlimage = new cApiUpload($imageid);
                        $meta    = new cApiUploadMeta();
                        $meta->loadByUploadIdAndLanguageId($imageid, $lang);

                        $MOD["termine"][$MOD["zaehler"]]["image"]                = $tlimage->get('filename');
                        $MOD["termine"][$MOD["zaehler"]]["imageid"]              = $imageid;
                        $MOD["termine"][$MOD["zaehler"]]["imagedir"]             = $tlimage->get('dirname');
                        $MOD["termine"][$MOD["zaehler"]]["imagefullpath"]        =
                            "upload/" . $tlimage->get('dirname') . $tlimage->get('filename');
                        $MOD["termine"][$MOD["zaehler"]]["medianame"]            =
                            stripslashes($meta->get("medianame"));
                        $MOD["termine"][$MOD["zaehler"]]["description"]          =
                            stripslashes(str_replace("\"", "&quot;", $meta->get('description')));
                        $MOD["termine"][$MOD["zaehler"]]["copyright"]            =
                            stripslashes($meta->get('copyright'));
                        $MOD["termine"][$MOD["zaehler"]]["keywords"]             = stripslashes($meta->get('keywords'));
                        $MOD["termine"][$MOD["zaehler"]]["internal_description"] =
                            stripslashes($meta->get('internal_description'));
                    }
                    break;

                // Zyklus xten Tag
                case 16:
                    // Text
                    if ($element_type == '3') {
                        $MOD["termine"][$MOD["zaehler"]]["xtag"] = $db->f("value");
                    }
                    break;

                //  Ausschlussliste
                case 17:
                    // Text
                    if ($element_type == '3') {
                        $MOD["termine"][$MOD["zaehler"]]["aliste"] = $db->f("value");
                    }
                    break;

                // Termin hervorheben
                case 20:
                    // Text
                    if ($element_type == '3') {
                        $MOD["termine"][$MOD["zaehler"]]["highlight"] = $db->f("value");
                    }
                    break;

                // zusätzliche termine
                case 22:
                    // Text
                    if ($element_type == '3') {
                        $MOD["termine"][$MOD["zaehler"]]["zutermine"] = $db->f("value");
                    }
                    break;
            }
        }
        $MOD["datensaetze"]++;
    }

    $MOD["termine"] = array_values($MOD["termine"]);

    if ($bDebug) {
        print "<br>Anzahl Termine nach Einlesen und 1. Umkopieren: " . count($MOD["termine"]);

        print "<br>Array nach Einlesen und 1. Umkopieren:<br><pre>";
        print_r($MOD["termine"]);
        print "</pre>";
    }

    // Ermittlung der zyklischen Termine und Löschen von Datensätzen, die nicht angezeigt werden müssen

    $zusatzterminliste = [];

    foreach ($MOD["termine"] as $termin) {
        // Zyklischer Termin
        if (($termin["zyklus"] != 'no' || $termin["zutermine"] != '') && $termin["idart"] != '') {
            $MOD_TL_zdate_array = makedatearray(
                $termin["datum1"],
                $termin["datum2"],
                $termin["zyklus"],
                $termin["xtag"],
                $termin["aliste"],
                $MOD["checkdate_von"],
                $MOD["checkdate_bis"],
                $termin["wtagezyklus"],
                $termin["wtagemon"],
                $termin["zutermine"]
            );

            if ($bDebug) {
                print "<br>Cycle Dates found!";
                print "<br>Count Cycle-Dates: " . count($MOD_TL_zdate_array);
            }

            if (count($MOD_TL_zdate_array) >= 1) {
                if ($termin["zyklus"] != 'no') {
                    $termin["datum2"] = '';
                }

                foreach ($MOD_TL_zdate_array as $zusatztermin) {
                    if ($termin["datum1"] != $zusatztermin) {
                        $termin2add                 = [];
                        $termin2add["datum1"]       = $zusatztermin;
                        $termin2add["datum2"]       = $zusatztermin;
                        $termin2add["zeit1"]        = $termin["zeit1"];
                        $termin2add["zeit2"]        = $termin["zeit2"];
                        $termin2add["ort"]          = $termin["ort"];
                        $termin2add["titel"]        = $termin["titel"];
                        $termin2add["idart"]        = $termin["idart"];
                        $termin2add["text"]         = $termin["text"];
                        $termin2add["linktype"]     = $termin["linktype"];
                        $termin2add["linkexternal"] = $termin["linkexternal"];
                        $termin2add["linkinternal"] = $termin["linkinternal"];
                        $termin2add["linkfilename"] = $termin["linkfilename"];
                        $termin2add["linkdesc"]     = $termin["linkdesc"];
                        $termin2add["linkframe"]    = $termin["linkframe"];
                        $termin2add["kategorie"]    = $termin["kategorie"];
                        $termin2add["highlight"]    = $termin["highlight"];
                        array_push($zusatzterminliste, $termin2add);
                    }
                }
            }
        }
    }

    if ($bDebug) {
        print "zusatztermine " . count($zusatzterminliste);
        print "anzahl vor zusatztermine einfügen: " . count($MOD["termine"]);
    }

    $MOD["termine"] = array_merge($MOD["termine"], $zusatzterminliste);

    if ($bDebug) {
        print "anzahl nach zusatztermine einfügen: " . count($MOD["termine"]);
        print "<br>Anzahl Termine nach Sortierung, VOR Bereinigung nach Datum und Kategorie: " . count($MOD["termine"]);
        print "<br>Array nach Sortierung, VOR Bereinigung nach Datum und Kategorie:<br><pre>";
        print_r($MOD["termine"]);
        print "</pre>";
        print "<br>Zu berücksichtigende Themen-Kategorien:<br><pre>";
        print_r($selectedcategories);
        print "</pre>";
    }

    // Prüfung ob Termin im aktuellen Anzeigezeitrahmen ist

    $MOD["zaehler2"] = count($MOD["termine"]);

    // before stripping, keep this version of $MOD["termine"] as a base for full calendar display
    // just clear this new list and take out unwanted category entries
    $calendarlist = $MOD["termine"];
    foreach ($calendarlist as $key => $termin) {
        // Now check if categories fit; if not, mark for deletion!
        $MOD_TL_termin_del = false;

        if ($termin["datum1"] == "") {
            $MOD_TL_termin_del = true;
        }

        $terminCatArray = explode(";", $termin["kategorie"]);
        $foundit        = false;
        foreach ($terminCatArray as $key2 => $value) {
            if (in_array($value, $selectedcategories)) {
                $foundit = true;
            }
        }

        if (!$foundit) {
            $MOD_TL_termin_del = true;
        }

        // now delete if necessary
        if ($MOD_TL_termin_del) {
            unset ($calendarlist[$key]);
        }
    }

    foreach ($MOD["termine"] as $key => $termin) {
        $MOD_TL_termin_del = false;

        // case 1: check dates determined by module

        if (!isset($_GET["MOD_TL_newdate"])) {
            // note: dates which are up and running RIGHT now are both listed on passed events and current events, as they are never marked for deletion
            if (!(($termin["datum1"] >= $MOD["checkdate_von"] && $termin["datum1"] <= $MOD["checkdate_bis"])
                || ($termin["datum2"] >= $MOD["checkdate_von"] && $termin["datum2"] <= $MOD["checkdate_bis"]))
            ) {
                $MOD_TL_termin_del = true;
            }

            if ($MOD["show_teaser"] && !$termin["teaser"]) {
                $MOD_TL_termin_del = true;
            }
        } else {
            // case 2: check dates determined by calendar

            if (!(($termin["datum1"] <= $MOD["checkdate_von"] && $termin["datum2"] >= $MOD["checkdate_bis"]))) {
                $MOD_TL_termin_del = true;
            }
        }

        // Now check if categories fit; if not, mark for deletion!
        $terminCatArray = explode(";", $termin["kategorie"]);
        $foundit        = false;
        foreach ($terminCatArray as $key2 => $value) {
            if (in_array($value, $selectedcategories)) {
                $foundit = true;
            }
        }

        if (!$foundit) {
            $MOD_TL_termin_del = true;
        }

        // now delete if necessary
        if ($MOD_TL_termin_del) {
            unset ($MOD["termine"][$key]);
        }
    }

    if (!empty($MOD["termine"])) {
        // nach Datum und Zeit sortieren
        foreach ($MOD["termine"] as $key => $array) {
            $datum1Array[$key] = $array["datum1"];
            $datum2Array[$key] = $array["datum2"];
            $zeit1Array[$key]  = $array["zeit1"];
            $zeit2Array[$key]  = $array["zeit2"];
            $titelArray[$key]  = $array["titel"];
            $ortArray[$key]    = $array["ort"];
            $idArray[$key]     = $array["id"];
        }

        $MOD["sort"] = ($MOD["sort"] == 'DESC') ? "DESC" : "ASC";
        $upordown    = constant('SORT_' . $MOD["sort"]);
        array_multisort(
            $datum1Array,
            $upordown,
            SORT_STRING,
            $zeit1Array,
            $upordown,
            SORT_STRING,
            $zeit2Array,
            $upordown,
            SORT_STRING,
            $MOD["termine"]
        );
    }

    // Extend array with additional values, mainly for date expressions

    $terminCount = count($MOD["termine"]);

    if ($bDebug) {
        print "<br>Anzahl Termine nach Sortierung, nach Bereinigung nach Datum und Kategorie: " . $terminCount;
    }

    $counter = 1;

    foreach ($MOD["termine"] as &$termin) {
        // do this first, as we need parts of it at the end of the routine
        $MOD_TL_datum1_array = explode("-", $termin["datum1"]);
        $MOD_TL_datum2_array = explode("-", $termin["datum2"]);

        // Prepare all sorts of date strings for both dates
        $dateHelper                 = new DateTime($termin["datum1"]);
        $termin["date1_month"]      = $dateHelper->format("m");
        $termin["date1_monthnum"]   = $dateHelper->format("n");
        $termin["date1_monthshort"] = $months_short[$lang][$termin["date1_monthnum"]];
        $termin["date1_monthfull"]  = $months_full[$lang][$termin["date1_monthnum"]];
        $termin["date1_yearshort"]  = $dateHelper->format("y");
        $termin["date1_yearfull"]   = $dateHelper->format("Y");
        $termin["date1_day"]        = $dateHelper->format("d");
        $termin["date1_daynum"]     = $dateHelper->format("w");
        $termin["date1_dayshort"]   = $days_short[$lang][$termin["date1_daynum"]];
        $termin["date1_dayfull"]    = $days_full[$lang][$termin["date1_daynum"]];

        $replace1             = [
            " " . $dateHelper->format("D") . " ",
            " " . $dateHelper->format("l") . " ",
            " " . $dateHelper->format("M") . " ",
            " " . $dateHelper->format("F") . " ",
        ];
        $replace2             = [
            " " . $termin["date1_dayshort"] . " ",
            " " . $termin["date1_dayfull"] . " ",
            " " . $termin["date1_monthshort"] . " ",
            " " . $termin["date1_monthfull"] . " ",
        ];
        $termin["date1_lang"] =
            trim(str_replace($replace1, $replace2, " " . $dateHelper->format($MOD["dateformat"]) . " "));

        if ($termin["datum2"] != "") {
            $dateHelper                 = new DateTime($termin["datum2"]);
            $termin["date2_month"]      = $dateHelper->format("m");
            $termin["date2_monthnum"]   = $dateHelper->format("n");
            $termin["date2_monthshort"] = $months_short[$lang][$termin["date2_monthnum"]];
            $termin["date2_monthfull"]  = $months_full[$lang][$termin["date2_monthnum"]];
            $termin["date2_yearshort"]  = $dateHelper->format("y");
            $termin["date2_yearfull"]   = $dateHelper->format("Y");
            $termin["date2_day"]        = $dateHelper->format("d");
            $termin["date2_daynum"]     = $dateHelper->format("w");
            $termin["date2_dayshort"]   = $days_short[$lang][$termin["date2_daynum"]];
            $termin["date2_dayfull"]    = $days_full[$lang][$termin["date2_daynum"]];
            $replace1                   = [
                " " . $dateHelper->format("D") . " ",
                " " . $dateHelper->format("l") . " ",
                " " . $dateHelper->format("M") . " ",
                " " . $dateHelper->format("F") . " ",
            ];
            $replace2                   = [
                " " . $termin["date2_dayshort"] . " ",
                " " . $termin["date2_dayfull"] . " ",
                " " . $termin["date2_monthshort"] . " ",
                " " . $termin["date2_monthfull"] . " ",
            ];
            $termin["date2_lang"]       =
                trim(str_replace($replace1, $replace2, " " . $dateHelper->format($MOD["dateformat"]) . " "));
        }

        $termin["oneday"]      = $termin["datum1"] == $termin["datum2"];
        $termin["groupeddate"] = $termin["prevdate"] == $termin["datum1"];

        $termin["prevdate"] = $termin["datum1"];
        $MOD["gefunden"]    = true;

        // cut the array if a limiter is given
        if (!($MOD["anzahl_termine"] >= 0 && $counter > $MOD["anzahl_termine"])) {
            array_push($terminliste, $termin);
        }
        $counter++;
    }
}

$labels = [
    'oclock'         => mi18n("oclock"),
    'fullday'        => mi18n("fullday"),
    'from'           => mi18n("from"),
    'until'          => mi18n("to"),
    'timeoption'     => mi18n("headline_timeoptions"),
    'categoryoption' => mi18n("headline_categoryoptions"),
    'send'           => mi18n("search"),
    'datefrom'       => mi18n("date_from"),
    'dateuntil'      => mi18n("date_until"),
    'dateframe'      => mi18n("dateframe"),
    'today'          => mi18n("today"),
    'thisweek'       => mi18n("thisweek"),
    'thismonth'      => mi18n("thismonth"),
    'thisyear'       => mi18n("thisyear"),
];

// use smarty template to output header text
$tpl = cSmartyFrontend::getInstance();
$tpl->assign('label', $labels);
$tpl->assign('termine', $terminliste);
$tpl->assign('searcherrortext', mi18n("extend_search"));
// values needed for FEU-Form display
$tpl->assign('datefrom', $MOD_TS_datum_von);
$tpl->assign('dateuntil', $MOD_TS_datum_bis);
$tpl->assign('formaction', $REQUEST_URI);
$tpl->assign('MOD', $MOD);
$tpl->assign('categories_checkbox', createCheckbox($MOD["categories_sel"], $feuselectedcat, "categories", $lang, 130));
$tpl->assign('categories_dropdown', createSel($MOD["categories_sel"], $feuselectedcat, "categories", $lang));
$tpl->assign('errors', $errors);

if ($bDebug) {
    print "<pre>";
    print_r($MOD["termine"]);
    print "</pre>";
}

// if module wants calendar to be displayed: create it
if ($MOD["modconfigbased"] == "calendar") {
    $sKalender = new sKalender();
    $sKalender->setDate($MOD["newdate_array"][2], $MOD["newdate_array"][1], $MOD["newdate_array"][0]);
    $tpl->assign('calendar', $sKalender->printCalender());
    $tpl->display($MOD["tpl_calendar"]);
    if ($calendarrequest && count($terminliste) > 0) {
        $tpl->display($MOD["tpl_results"]);
    }
}

if ($MOD["modconfigbased"] == "feuform") {
    $tpl->display($MOD["tpl_feuform"]);
    if (count($terminliste) > 0) {
        $tpl->display($MOD["tpl_results"]);
    }
    if ($feurequest && count($terminliste) == 0) {
        $tpl->display('results_none.tpl');
    }
}

if ($MOD["modconfigbased"] == "module" && count($terminliste) > 0) {
    $tpl->display($MOD["tpl_results"]);
}

/*** classes **/

if (!class_exists('sKalender')) {
    class sKalender
    {
        var $MOD_TL_akt_datum;

        var $MOD_TL_admin;

        public function __construct()
        {
            // Verhindert mögliche Endlosloops Ausführungszeit max 1 Sek.
            //set_time_limit( 1 );

            // wird der Funktion ein Parameter übergeben, wird diese dem Key "MOD_TL_admin" zugeordnet
            if (func_num_args() == 1) {
                $this->MOD_TL_admin = func_get_arg(0);
            } else {
                $this->MOD_TL_admin = false;
            }

            // aktuelles Datum in Array MOD_TL_akt_datum schreiben
            $this->MOD_TL_akt_datum = getdate();
        }

        public function setDate($MOD_TL_tag, $MOD_TL_monat, $MOD_TL_jahr)
        {
            // prüft, ob alle Werte übergeben wurden und schreibt diese dann ins Array
            if ($MOD_TL_tag != "" && $MOD_TL_monat != "" && $MOD_TL_jahr != "") {
                $this->MOD_TL_akt_datum["mday"] = $MOD_TL_tag * 1;  // führende Nullen entfernen
                $this->MOD_TL_akt_datum["mon"]  = $MOD_TL_monat * 1; // führende Nullen entfernen
                $this->MOD_TL_akt_datum["year"] = $MOD_TL_jahr;

                return true;
            } else {
                return false;
            }
        }

        private function getDateByMonth($MOD_TL_neuer_monat)
        {
            return mktime(
                0,
                0,
                0,
                ($this->MOD_TL_akt_datum["mon"] + $MOD_TL_neuer_monat),
                $this->MOD_TL_akt_datum["mday"],
                $this->MOD_TL_akt_datum["year"]
            );
        }

        public function printCalender()
        {
            // Globale Variablen definieren
            global $lang, $days_short, $months_full;
            global $MOD;
            global $idart;

            $output = "";

            if ($this->MOD_TL_akt_datum == "") {
                return false;
            } else {
                $MOD_TL_datum = $this->MOD_TL_akt_datum;
            }

            $MOD_TL_wochenbeginn = 2;    // Die Woche beginnt mit Montag, PHP beginnt mit Sonntag = 1
            $MOD_TL_admin        = "&MOD_TL_admin=" . $this->MOD_TL_admin;

            $output .= '<div id="mod_sK">
<table>
<tr>';
            // Einen Monat zurück
            $MOD_TL_neues_datum = $this->getDateByMonth(-1);
            $MOD_TL_newdate     = date("Y", $MOD_TL_neues_datum) . '-' . date("n", $MOD_TL_neues_datum) . '-1';
            $output             .= '<td class="navl"><a  href="front_content.php?idart=' . $idart . '&MOD_TL_newdate='
                . $MOD_TL_newdate . '" title="' . mi18n("vorheriger Monat") . '">&lt;&lt;</a></td>';

            // aktueller Monat
            $MOD_TL_neues_datum = $this->getDateByMonth(0);
            $MOD_TL_newdate     = date("Y", $MOD_TL_neues_datum) . '-' . date("n", $MOD_TL_neues_datum) . '-1';

            // Dropdownfeld aufbauen
            $MOD_TL_arry_years = count($MOD["array_down"]); // Anzahl der Jahre
            $MOD_TL_dropdown   =
                '<select name="MOD_TL_newdate" onchange="parent.frames.top.location.href = this.value">';
            $MOD_TL_dropdown   .= '<option value="' . $MOD_TL_newdate . '">' . $months_full[$lang][$MOD_TL_datum["mon"]]
                . '&nbsp;' . $MOD_TL_datum["year"] . '</option>';

            for ($MOD_TL_dyears = 0; $MOD_TL_dyears < $MOD_TL_arry_years; $MOD_TL_dyears++) {
                for ($MOD_TL_dmonth = 1; $MOD_TL_dmonth <= 12; $MOD_TL_dmonth++) {
                    $MOD_TL_dropdown .= '<option value="front_content.php?subid=' . $subid . '&idart=' . $idart
                        . '&MOD_TL_newdate=' . $MOD["array_down"][$MOD_TL_dyears] . '-' . $MOD_TL_dmonth . '-1">'
                        . $months_full[$lang][$MOD_TL_dmonth] . '&nbsp;' . $MOD["array_down"][$MOD_TL_dyears]
                        . '</option>';
                }
            }
            $MOD_TL_dropdown .= '</select>';

            if (!$MOD["ddownyn"]) {
                $output .= '<td class="navm"><a href="front_content.php?idart=' . $MOD["idart"] . '&MOD_TL_newdate='
                    . $MOD_TL_newdate . '&MOD_TL_show=m" title="' . mi18n("Monats&uuml;bersicht anzeigen.") . '">'
                    . $months_full[$lang][$MOD_TL_datum["mon"]] . '&nbsp;' . $MOD_TL_datum["year"] . '</a></td>';
            } else {
                $output .= '<td class="navm">' . $MOD_TL_dropdown . '</td>';
            }

            // Einen Monat vor
            $MOD_TL_neues_datum = $this->getDateByMonth(+1);
            $MOD_TL_newdate     = date("Y", $MOD_TL_neues_datum) . '-' . date("n", $MOD_TL_neues_datum) . '-1';
            $output             .= '<td class="navr"><a href="front_content.php?idart=' . $idart . '&MOD_TL_newdate='
                . $MOD_TL_newdate . '" title="' . mi18n("n&auml;chster Monat") . '">&gt;&gt;</a></td>';
            $output             .= '
</tr>
</table>
<table>';

            // Ermittle die maximalen Tage für den aktuellen Monat
            $MOD["tag_max"] = date("t", mktime(0, 0, 0, $MOD_TL_datum["mon"], 1, $MOD_TL_datum["year"]));
            for ($MOD_TL_tag_aktuell = 1; $MOD_TL_tag_aktuell <= $MOD["tag_max"]; $MOD_TL_tag_aktuell++) {
                $MOD_TL_tag_nummer =
                    date("w", mktime(0, 0, 0, $MOD_TL_datum["mon"], $MOD_TL_tag_aktuell, $MOD_TL_datum["year"])) + 1;
                if ($MOD_TL_tag_aktuell == 1) {
                    $output .= '
<tr>
<td class="wtag">' . $days_short[$lang][1] . '</td>
<td class="wtag">' . $days_short[$lang][2] . '</td>
<td class="wtag">' . $days_short[$lang][3] . '</td>
<td class="wtag">' . $days_short[$lang][4] . '</td>
<td class="wtag">' . $days_short[$lang][5] . '</td>
<td class="wtag">' . $days_short[$lang][6] . '</td>
<td class="wtag">' . $days_short[$lang][0] . '</td>
</tr>';

                    // Leere Felder am Anfang / Ende
                    $output .= '<tr>';
                    for (
                        $MOD_TL_check_tag = $MOD_TL_wochenbeginn; $MOD_TL_tag_nummer != $MOD_TL_check_tag;
                        $MOD_TL_check_tag++
                    ) {
                        $output .= '<td>&nbsp;</td>';
                        if ($MOD_TL_tag_nummer == 1 && $MOD_TL_check_tag == 7)    // Workaround !!!
                        {
                            $output .= ($this->getCell($MOD_TL_tag_aktuell));
                            break;
                        }

                        if ($MOD_TL_check_tag > 15)    // Workaround !!!
                        {
                            break;
                        }
                    }

                    if ($MOD_TL_tag_nummer == $MOD_TL_check_tag) {
                        $output .= $this->getCell($MOD_TL_tag_aktuell);
                    }
                } else {
                    if ($MOD_TL_tag_nummer == $MOD_TL_wochenbeginn) {
                        $output .= '
</tr>
<tr>';
                    }
                    $output .= $this->getCell($MOD_TL_tag_aktuell);
                }
            }

            $MOD_TL_tag_aktuell = $MOD_TL_tag_aktuell - 6;

            $MOD_TL_tag_nummer = date("w", mktime(0, 0, 0, $MOD_TL_datum["mon"], $MOD_TL_tag_aktuell, $MOD_TL_datum["year"]));

            while ($MOD_TL_tag_nummer != $MOD_TL_wochenbeginn) {
                $output .= ' <td>&nbsp</td>';
                $MOD_TL_tag_aktuell++;
                $MOD_TL_tag_nummer =
                    date("w", mktime(0, 0, 0, $MOD_TL_datum["mon"], $MOD_TL_tag_aktuell, $MOD_TL_datum["year"]));
            }

            $output .= '
</tr>
</table>
</div>';

            return $output;
        }

        private function checkDayFor1($MOD_TL_datum_check)
        {
            global $calendarlist;
            $MOD_TL_check_ok = false;
            foreach ($calendarlist as $termin) {
                if ($termin["datum1"] == $MOD_TL_datum_check
                    && ($termin["datum2"] == ''
                        || $termin["datum2"] == $termin["datum1"])
                ) {
                    $MOD_TL_status_ok = true;
                }
            }

            return $MOD_TL_check_ok;
        }

        private function checkDayFor2($MOD_TL_datum_check)
        {
            global $calendarlist;
            $MOD_TL_check_ok = false;
            foreach ($calendarlist as $termin) {
                // here seems to be a bug. The list contains a lot of element with date1 being empty, don't know why. Filter them out here!
                if ($termin["datum1"] != "" && $MOD_TL_datum_check >= $termin["datum1"]
                    && $MOD_TL_datum_check <= $termin["datum2"]
                ) {
                    $MOD_TL_check_ok = true;
                }
            }

            return $MOD_TL_check_ok;
        }

        private function checkStatus1($MOD_TL_datum_check) // Tag mit reservierten Terminen
        {
            global $calendarlist;
            $MOD_TL_status_ok = false;
            foreach ($calendarlist as $termin) {
                if ($termin["datum1"] == $MOD_TL_datum_check && $termin["status"] == '1') {
                    $MOD_TL_status_ok = true;
                }
            }

            return $MOD_TL_status_ok;
        }

        private function checkStatus2($MOD_TL_datum_check) // Tag mit gebuchten Terminen
        {
            global $calendarlist;
            $MOD_TL_status_ok = false;
            foreach ($calendarlist as $termin) {
                if ($termin["datum1"] == $MOD_TL_datum_check && $termin["status"] == '2') {
                    $MOD_TL_status_ok = true;
                }
            }

            return $MOD_TL_status_ok;
        }

        private function getCell($MOD_TL_tag)
        {
            global $MOD;

            $MOD_TL_datum_aktuell = date(
                "Y-m-d",
                mktime(0, 0, 0, date("m", $this->getDateByMonth(0)), $MOD_TL_tag, date("Y", $this->getDateByMonth(0)))
            );
            $MOD_TL_datum_title   = date(
                "d.m.Y",
                mktime(0, 0, 0, date("m", $this->getDateByMonth(0)), $MOD_TL_tag, date("Y", $this->getDateByMonth(0)))
            );
            $MOD_TL_wochentag     = date(
                "w",
                mktime(0, 0, 0, date("m", $this->getDateByMonth(0)), $MOD_TL_tag, date("Y", $this->getDateByMonth(0)))
            );

            $MOD_TL_termin_ok = false;
            if ($MOD["anzeigemodus"]) {
                if ($MOD_TL_wochentag == 6 || $MOD_TL_wochentag == 0) {
                    // Wochenende
                    $MOD_TL_style = "wochenende";
                } else {
                    // Normal
                    $MOD_TL_style = "wochentag";
                }

                if (date("Y-m-d") == $MOD_TL_datum_aktuell) {
                    // Heute
                    $MOD_TL_style = "heute";
                }

                // Tag mit direkten Terminen
                if ($this->checkDayFor1($MOD_TL_datum_aktuell)) {
                    $MOD_TL_termin_ok = true;
                    if ($MOD_TL_wochentag == 6 || $MOD_TL_wochentag == 0) {
                        // Wochenende
                        $MOD_TL_style = "belegtwe";
                    } else {
                        $MOD_TL_style = "belegt";
                    }
                } else {
                    // Tag mit indirekten Terminen
                    if ($this->checkDayFor2($MOD_TL_datum_aktuell)) {
                        $MOD_TL_termin_ok = true;
                        if ($MOD_TL_wochentag == 6 || $MOD_TL_wochentag == 0) {
                            // Wochenende
                            $MOD_TL_style = "tangiertwe";
                        } else {
                            $MOD_TL_style = "tangiert";
                        }
                    }
                }
            } else {
                $MOD_TL_style = "wochentag"; // 
                if ($this->checkStatus1($MOD_TL_datum_aktuell))    // Tag mit reservierten Terminen
                {
                    $MOD_TL_termin_ok = true;
                    $MOD_TL_style     = "tangiert";
                }
                if ($this->checkStatus2($MOD_TL_datum_aktuell))    // Tag mit gebuchten Terminen
                {
                    $MOD_TL_termin_ok = true;
                    $MOD_TL_style     = "belegt";
                }
            }

            global $MOD;

            if ($MOD_TL_termin_ok) {
                if ($MOD["linkyn"]) {
                    $MOD_TL_val = '<td class="' . $MOD_TL_style . '"><a class="tag" href="front_content.php?idart='
                        . $MOD["idart"] . '&MOD_TL_newdate=' . $MOD_TL_datum_aktuell . '&MOD_TL_show=t" title="'
                        . mi18n("Termine für den") . ' ' . $MOD_TL_datum_title . ' ' . mi18n("anzeigen") . '.">'
                        . $MOD_TL_tag . '</a></td>';
                } else {
                    if ($MOD["anzeigemodus"]) {
                        $MOD_TL_val =
                            '<td class="' . $MOD_TL_style . '"><a class="tag" title="' . mi18n("Für den") . ' '
                            . $MOD_TL_datum_title . ' ' . mi18n("existieren Termine") . '.">' . $MOD_TL_tag
                            . '</a></td>';
                    } else {
                        $MOD_TL_val =
                            '<td class="' . $MOD_TL_style . '"><a class="tag" title="' . mi18n("gebucht") . '">'
                            . $MOD_TL_tag . '</a></td>';
                    }
                }
            } else {
                if ($MOD["anzeigemodus"]) {
                    $MOD_TL_val = '<td class="' . $MOD_TL_style . '"><a class="tag" title="' . mi18n("Für den") . ' '
                        . $MOD_TL_datum_title . ' ' . mi18n("existieren keine Termine.") . '">' . $MOD_TL_tag
                        . '</a></td>';
                } else {
                    $MOD_TL_val =
                        '<td class="' . $MOD_TL_style . '"><a class="tag" title="' . mi18n("frei") . '">' . $MOD_TL_tag
                        . '</a></td>';
                }
            }

            return $MOD_TL_val;
        }
    }
}

?>