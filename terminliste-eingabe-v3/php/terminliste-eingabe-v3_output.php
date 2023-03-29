    <?php
    /***********************************************
    * CONTENIDO MODUL - OUTPUT
    *
    * Modulname      :     Termineingabe/anzeige universal
    * Version        :     3.0
    * Author         :     schlaucher (Original 4.8 version)
    * 4.9 Adaptation :     Viktor Lehmann, TONE2TONE
    * Created        :     12-07-2006 (schlaucher)
    * Modified       :     20.08.2015 (T2T)
    ************************************************/

    /***********************************************
    * Formularfelder:
    *
    * $MOD_TE_termin[0] = Startdatum
    * $MOD_TE_termin[1] = Startzeit
    * $MOD_TE_termin[2] = Enddatum
    * $MOD_TE_termin[3] = Endzeit
    * $MOD_TE_termin[4] = Ort
    * $MOD_TE_termin[5] = Ortdetail
    * $MOD_TE_termin[6] = Titel
    * $MOD_TE-termin[7] = Check für Teaser
    * $MOD_TE_termin[8] = Bemerkung CMS_HTML[8]
    * $MOD_TE_termin[9] = Link CMS_LINK[9]
    * $MOD_TE_termin[10] = Termin Zyklus
    * $MOD_TE_termin[11] = Termin Zyklus Wochentage
    * $MOD_TE_termin[12] = jeden 1.,2.,3.,4.,5. Wochentag
    * $MOD_TE_termin[13] = Kategorien A
    * $MOD_TE_termin[14] = Image
    * $MOD_TE_termin[15] = Status
    * $MOD_TE_termin[16] = Termin Zyklus jeden x-ten Tag
    * $MOD_TE_termin[17] = Termin Zyklus Ausschlussliste
    * $MOD_TE_termin[18] = Termin Zyklus Anzeigemodus1 (alle/aktuelle)
    * $MOD_TE_termin[19] = Termin Zyklus Anzeigemodus2(Anzahl)
    * $MOD_TE_termin[20] = Termin in der Terminliste hervorheben
    * $MOD_TE_termin[21] = Kategorien B (erstmal deaktiviert)
    * $MOD_TE_termin[22] = zusätzliche Termine
    ************************************************/

    // To Do:
    // Re-Introduce Kategorie Output to FEU version
    // Re-Introduce Status
    // Re-Introduce Time Settings
    // Re-Introduce Cycle Settings
    // Re-Introduce Image. Deleted here, code is too oldfashioned.
       
       
    // Includes
    cInclude("module", "functions.terminliste.php");
    cInclude("frontend", "includes/functions.t2t.php");
    $cycles = array("no"=>"kein Zyklus", "tag"=>"täglich","xtag"=>"jeden X-ten Tag", "woche"=>"wöchentlich", "wtage"=>"jeden X-ten Wochentag Y", "14tag"=>"14-tägig", "monat"=>"monatlich", "jahr"=>"jährlich");
    $cycleweekdays = array( "So"=>"Sonntag", "Mo"=>"Montag", "Di"=>"Dienstag","Mi"=>"Mittwoch", "Do"=>"Donnerstag", "Fr"=>"Freitag", "Sa"=>"Samstag");
    $cycleweeknums = array("1"=>"1.", "2"=>"2.","3"=>"3.", "4"=>"4.", "5"=>"5.");

    $days_short[1]       = array(0=>"So", 1=>"Mo", 2=>"Di", 3=>"Mi", 4=>"Do", 5=>"Fr", 6=>"Sa");
    $days_full[1]        = array(0=>"Sonntag", 1=>"Montag", 2=>"Dienstag", 3=>"Mittwoch", 4=>"Donnerstag", 5=>"Freitag", 6=>"Samstag");
    $months_short[1]     = array(1=>"Jan", 2=>"Feb", 3=>"Mär", 4=>"Apr", 5=>"Mai", 6=>"Jun", 7=>"Jul", 8=>"Aug", 9=>"Sep", 10=>"Okt", 11=>"Nov", 12=>"Dez");
    $months_full[1]      = array(1=>"Januar", 2=>"Februar", 3=>"März", 4=>"April", 5=>"Mai", 6=>"Juni", 7=>"Juli", 8=>"August", 9=>"September", 10=>"Oktober",11=>"November", 12=>"Dezember");   


    // Client Settings
    $categories[1]    = getEffectiveSetting('terminliste', 'categories', '');
    $highlightlist    = getEffectiveSetting('terminliste', 'css_classes', '');
    $highlightlist    = ( $highlightlist != "" ) ? explode(";", "--;".$highlightlist) : "";

    // Definition benötigter Variablen $MOD_TE_[Name]
    $MOD_TE_zaehler = 0;
    $MOD_TE_date = date("Y-m-d H:i:s");
    $MOD_TE_today = date("Y-m-d");
    $MOD_TE_author = $auth->auth["uname"];
    $MOD_TE_tage = array(mi18n("Sonntag"),mi18n("Montag"),mi18n("Dienstag"),mi18n("Mittwoch"),mi18n("Donnerstag"),mi18n("Freitag"),mi18n("Samstag"));
    $MOD_TE_tage_abk = array(mi18n("So"),mi18n("Mo"),mi18n("Di"),mi18n("Mi"),mi18n("Do"),mi18n("Fr"),mi18n("Sa"));
    $MOD_TE_navmod_link = 'front_content.php?&idcat='.$idcat.'';
    $MOD_TE_checkdate_von = '0000-00-00';
    $MOD_TE_checkdate_bis = '9999-99-99';

    // Variablen aus Input Bereich

    // Anzeige des Images in der Vorschau
    $MOD_TE_img_show = ("CMS_VALUE[11]" == 'yes')  ? true : false;
    $MOD_TE_imgw = "CMS_VALUE[12]"; // Maximale Bildbreite
    $MOD_TE_imgh = "CMS_VALUE[13]"; // Maximale Bildhöhe
    $MOD_TE_imgar = "CMS_VALUE[14]"; // Bild Ausrichtung
    $MOD_TE_imgva= "CMS_VALUE[15]"; // Vertikaler Raum
    $MOD_TE_imgha = "CMS_VALUE[16]"; // Horizontaler Raum
    $MOD_TE_ortsliste_array = ( "CMS_VALUE[17]" != "" ) ? explode(';',"CMS_VALUE[17]") : ""; // Ortsauswahlliste
    $tpl_editor      =  ( "CMS_VALUE[24]" != "" ) ? "CMS_VALUE[24]" : "frontendeditor.tpl";
    $tpl_fedisplay   =  ( "CMS_VALUE[25]" != "" ) ? "CMS_VALUE[25]" : "frontenddisplay.tpl";


    // SQL Abfrage über den aktuellen Artikel
    $MOD_TE_sql  = "SELECT  ARTLANG.idart, CONTENT.value, CONTENT.typeid, CONTENT.idtype, ARTLANG.idartlang ";
    $MOD_TE_sql .= "FROM ".$cfg["tab"]["cat_art"]." AS CATART,";
    $MOD_TE_sql .= " ".$cfg["tab"]["art_lang"]." AS ARTLANG,";
    $MOD_TE_sql .= " ".$cfg["tab"]["cat"]." AS CAT,";
    $MOD_TE_sql .= " ".$cfg["tab"]["content"]." AS CONTENT,";
    $MOD_TE_sql .= " ".$cfg["tab"]["cat_lang"]." AS CATLANG ";
    $MOD_TE_sql .= "WHERE ARTLANG.idart = CATART.idart ";
    $MOD_TE_sql .= "AND CATART.idcat = CAT.idcat ";
    $MOD_TE_sql .= "AND ARTLANG.idartlang = CONTENT.idartlang ";
    $MOD_TE_sql .= "AND ARTLANG.idlang = '".$lang."' ";
    $MOD_TE_sql .= "AND ARTLANG.idart = '".$idart."' ";
    $MOD_TE_sql .= "AND CAT.idclient = '".$client."' ";
    $MOD_TE_sql .= "AND CATART.idcat = CATLANG.idcat ";
           
    $db->query($MOD_TE_sql); // Artikelangaben einlesen

    // überprüfen, ob im Editiermodus aufgerufen

           
        if ($db->numRows()> 0) // Datenfelder durchlaufen und alle CMS_TEXT Elemente in Array schreiben
            {
            $MOD_TE_termin = array(); // Array für die Termininhalte
            $MOD_TE_exists = array(); // Array für Prüfung, ob Datensatz bereits existiert
           
            $db->nextRecord();
            $idartlang = $db->f("idartlang");
            for ($MOD_TE_zaehler = 0; $MOD_TE_zaehler<$db->numRows(); $MOD_TE_zaehler++)
                {
                if ( $db->f("idtype") == 3 ) // = CMS_TEXT
                    {
                    $MOD_TE_typeid = $db->f("typeid");
                    $MOD_TE_termin[$MOD_TE_typeid] = $db->f("value");
                    $MOD_TE_exists[$MOD_TE_typeid] = 1;
                    }
                if ( $db->f("idtype") == "19" ) // = CMS_DATE
                    {
                $dom = new domDocument;
                $dom->loadXML($db->f("value"));
                $xml = simplexml_import_dom($dom);
                $date_Ymd  = date('Y-m-d',  (int)$xml->timestamp);
                $date_His  = date('H:i:s',  (int)$xml->timestamp);
                    $MOD_TE_typeid = $db->f("typeid");

                    $MOD_TE_termin[$MOD_TE_typeid."date"] = ($date_Ymd == "0000-00-00" ) ? "0000-00-00" : $date_Ymd;
                    $MOD_TE_termin[$MOD_TE_typeid."time"] = $date_His;
                    }             
                $db->nextRecord();
                }
            }
           
        if(!$MOD_TE_termin[6]) // falls noch kein Titel eingetragen, Seitentitel einlesen
            {
            $MOD_TE_sql    = "SELECT ARTLANG.title ";
            $MOD_TE_sql   .= "FROM ".$cfg["tab"]["art_lang"]." AS ARTLANG ";
            $MOD_TE_sql   .= "WHERE ARTLANG.idart = '".$idart."' ";
           
            $db->query($MOD_TE_sql);
            $db->nextRecord();
            $MOD_TE_termin[6] = $db->f("title");
            }
           
       // Presets
       //if(!$MOD_TE_termin[0]) $MOD_TE_termin[0] = $MOD_TE_today;   
        if(!$MOD_TE_termin[10]) $MOD_TE_termin[10] = 'no';
        if(!$MOD_TE_termin[11]) $MOD_TE_termin[11] = '';       
        if(!$MOD_TE_termin[12]) $MOD_TE_termin[12] = '';
        if(!$MOD_TE_termin[13]) $MOD_TE_termin[13] = '';
        if(!$MOD_TE_termin[15]) $MOD_TE_termin[15] = '0';
        if(!$MOD_TE_termin[16]) $MOD_TE_termin[16] = '0';   
        if(!$MOD_TE_termin[17]) $MOD_TE_termin[17] = '';   
        if(!$MOD_TE_termin[18]) $MOD_TE_termin[18] = '0';   
        if(!$MOD_TE_termin[19]) $MOD_TE_termin[19] = '-1';
       $MOD_TE_termin[20] = ( $MOD_TE_termin[20] = '') ? "--" : $MOD_TE_termin[20];
        if(!$MOD_TE_termin[22]) $MOD_TE_termin[22] = '';

        if($_POST["MOD_TE_senden"] == "1") // falls das Terminformular abgeschickt wurde, Daten in Datenbank schreiben
            {
          
            $MOD_TE_senden = false;
               
            $MOD_TE_termin[4] = ($MOD_TE_ortsliste == "") ? $MOD_TE_ort : $MOD_TE_ortsliste;         
            $MOD_TE_termin[5] = $MOD_TE_ort_detail;
            $MOD_TE_termin[6] = $MOD_TE_titel;
            $MOD_TE_termin[7] = $MOD_TE_teaser;
           
            // Erweiterte Felder für Terminzyklen und Kategorien
            $MOD_TE_termin[10] = $MOD_TE_cycle;
          
          // 9 = Filelink, already dealt with through own CMS type. We need its contents separate though for FEU display
          $dom = new domDocument;
          $dom->loadXML($MOD_TE_termin[9]);
          $xml = simplexml_import_dom($dom);
          $MOD_TE_termin["link"] = $xml->externallink;
          $MOD_TE_termin["linkframe"] = $xml->newwindow;
          $MOD_TE_termin["linkdesc"] = $xml->title;
             
          $collectweekdays = "";
          foreach ($_POST as $key => $value) {
             if ( substr($key, 0, 13) == 'cycleweekdays') {
                $collectweekdays .= $value.","; // use coma instead semikolon as an exception, keeps compatibility with functions.terminliste.php
             }
          }      
          $MOD_TE_termin[11] = ( $collectweekdays != "" && $MOD_TE_termin[10] =="wtage") ? substr($collectweekdays , 0, -1)  : "";

          $collectweeknums = "";
          foreach ($_POST as $key => $value) {
             if ( substr($key, 0, 13) == 'cycleweeknums') {
                $collectweeknums .= $value.","; // use coma instead semikolon as an exception
             }
          }      
          $MOD_TE_termin[12] = ( $collectweeknums != ""  && $MOD_TE_termin[10] =="wtage") ? substr($collectweeknums , 0, -1)  : "";
             
          $MOD_TE_termin[13] = "";
          foreach ($_POST as $key => $value) {
             if ( substr($key, 0, 10) == 'categories') {
                $MOD_TE_termin[13] .= $value.";";
             }
          }
          if ( $MOD_TE_termin[13] != "" ) { $MOD_TE_termin[13] = substr($MOD_TE_termin[13] , 0, -1); }
          
             
            $MOD_TE_termin[15] = $MOD_TE_status;
          $MOD_TE_termin[16] = (  $MOD_TE_termin[10] =="xtag" ) ? $MOD_TE_xtag : "";
             
          // check all deletable dates - eliminate if not correct, as it forces script errors.
          if ( $MOD_TE_aliste != "" ) {
             $checkzutermine = explode(",",$MOD_TE_aliste);
             foreach ($checkzutermine as $key=>$checktermin ) {
                $unixdate = strtotime($checktermin);
                   $recheck = date("Y-m-d", $unixdate);
                if ($recheck != $checktermin) {
                   unset($checkzutermine[$key]);
                   $errors[] = mi18n("error_dates_deletable_couldnotallbesaved");
                }
             }
          }
          $MOD_TE_termin[17] = implode (",",$checkzutermine);
             
            $MOD_TE_termin[18] = $MOD_TE_amodus1;
            $MOD_TE_termin[19] = $MOD_TE_amodus2;
          $MOD_TE_termin[20] = ( $MOD_TE_highlight == "--" ) ? "" : $MOD_TE_highlight;
             
          // check all additional dates - eliminate if not correct, as it forces script errors.
          if ( $MOD_TE_zutermine != "" ) {
             $checkzutermine = explode(",",$MOD_TE_zutermine);
             foreach ($checkzutermine as $key=>$checktermin ) {
                $unixdate = strtotime($checktermin);
                   $recheck = date("Y-m-d", $unixdate);
                if ($recheck != $checktermin) {
                   unset($checkzutermine[$key]);
                   $errors[] = mi18n("error_dates_additional_couldnotallbesaved");
                }
             }
          }
          $MOD_TE_termin[22] = implode (",",$checkzutermine);

            for ($MOD_TE_zaehler=4; $MOD_TE_zaehler<=22; $MOD_TE_zaehler++)
                {
             // Nothing to save in case of #8 and #9, will be done at a later stage
             if ( $MOD_TE_zaehler != 8 && $MOD_TE_zaehler != 9 && $MOD_TE_zaehler != 14 && $MOD_TE_zaehler != 21 ) {
                if($MOD_TE_exists[$MOD_TE_zaehler]==1)
                   {
                   $MOD_TE_sql    = "UPDATE ".$cfg["tab"]["content"]." ";
                   $MOD_TE_sql   .= "SET value='".$MOD_TE_termin[$MOD_TE_zaehler]."', lastmodified='".$MOD_TE_date."' ";
                   $MOD_TE_sql   .= "WHERE idartlang = '".$idartlang."' ";
                   $MOD_TE_sql   .= "AND idtype='3' ";
                   $MOD_TE_sql   .= "AND typeid='".$MOD_TE_zaehler."' ";
                   $db->query($MOD_TE_sql);
                   }
                else
                   {
                   $MOD_TE_sql    = "INSERT INTO ".$cfg["tab"]["content"]." ";
                   $MOD_TE_sql   .= "(idartlang, idtype, typeid, value, author, created, lastmodified) ";
                   $MOD_TE_sql   .= "VALUES('".$idartlang."', '3', '".$MOD_TE_zaehler."', '".$MOD_TE_termin[$MOD_TE_zaehler]."', '".$MOD_TE_author."', '".$MOD_TE_date."', '".$MOD_TE_date."')";
                   $db->query($MOD_TE_sql);
                   }               
                }
             }
          }
          
       if ($MOD_TE_termin[15] == '0') $MOD_TE_termin[status] = mi18n("frei");
        if ($MOD_TE_termin[15] == '1') $MOD_TE_termin[status] = mi18n("reserviert");
        if ($MOD_TE_termin[15] == '2') $MOD_TE_termin[status] = mi18n("gebucht");
           
        // Eingabefeld der Wochentage prüfen
        $MOD_TE_mo = (stristr($MOD_TE_termin[11], 'Mo')) ? "yes" : no;
        $MOD_TE_di = (stristr($MOD_TE_termin[11], 'Di')) ? "yes" : no;
        $MOD_TE_mi = (stristr($MOD_TE_termin[11], 'Mi')) ? "yes" : no;
        $MOD_TE_do = (stristr($MOD_TE_termin[11], 'Do')) ? "yes" : no;
        $MOD_TE_fr = (stristr($MOD_TE_termin[11], 'Fr')) ? "yes" : no;
        $MOD_TE_sa = (stristr($MOD_TE_termin[11], 'Sa')) ? "yes" : no;
        $MOD_TE_so = (stristr($MOD_TE_termin[11], 'So')) ? "yes" : no;
           
        $MOD_TE_jeder1 = (stristr($MOD_TE_termin[12], '1')) ? "yes" : no;
        $MOD_TE_jeder2 = (stristr($MOD_TE_termin[12], '2')) ? "yes" : no;
        $MOD_TE_jeder3 = (stristr($MOD_TE_termin[12], '3')) ? "yes" : no;
        $MOD_TE_jeder4 = (stristr($MOD_TE_termin[12], '4')) ? "yes" : no;
        $MOD_TE_jeder5 = (stristr($MOD_TE_termin[12], '5')) ? "yes" : no;
       
       // Determine additional date and time parameters per event
       if (strlen($MOD_TE_termin[1]) > 1) $MOD_TE_datum1_array = explode("-",$MOD_TE_termin[1]);
        if (strlen($MOD_TE_termin[2]) > 1) $MOD_TE_datum2_array = explode("-",$MOD_TE_termin[2]);
       
       
       if ( !$MOD_TE_termin[cycle] && $MOD_TE_termin[22] == "" ) {
          
             $dateHelper = new DateTime($MOD_TE_termin["1date"]);
             $MOD_TE_termin[date0_german] = $dateHelper->format("d.m.Y");
             $MOD_TE_termin[date0_day] = $dateHelper->format("d");
             $MOD_TE_termin[date0_month] = $dateHelper->format("m");
             $MOD_TE_termin[date0_year] = $dateHelper->format("Y");
             $MOD_TE_termin[date0_weekdaynum] = $dateHelper->format("w");
             $MOD_TE_termin[date0_dayfull] = $days_short[$lang][$MOD_TE_termin[date0_weekdaynum]];
             $MOD_TE_termin[date0_dayshort] = $days_full[$lang][$MOD_TE_termin[date0_weekdaynum]];
             
             $dateHelper = new DateTime($MOD_TE_termin["2date"]);
             $MOD_TE_termin[date2_german] = $dateHelper->format("d.m.Y");
             $MOD_TE_termin[date2_day] = $dateHelper->format("d");
             $MOD_TE_termin[date2_month] = $dateHelper->format("m");
             $MOD_TE_termin[date2_year] = $dateHelper->format("Y");
             $MOD_TE_termin[date2_weekdaynum] = $dateHelper->format("w");
             $MOD_TE_termin[date2_dayfull] = $days_short[$lang][$MOD_TE_termin[date2_weekdaynum]];
             $MOD_TE_termin[date2_dayshort] = $days_full[$lang][$MOD_TE_termin[date2_weekdaynum]];   
          
          } else {
             
             $MOD_TE_amodus2 = $MOD_TE_termin[19];
             // Wenn zusätzliche Termine bei Einzeltermin
             if($MOD_TE_termin[2] == '') $MOD_TE_termin[2] = $MOD_TE_termin[1];
             if( !$MOD_TE_termin[cycle] ) $MOD_TE_termin[10] = 'tag';
                
             $MOD_TE_zdate_array = makedatearray( $MOD_TE_termin[0],$MOD_TE_termin[2],
                                  $MOD_TE_termin[10],$MOD_TE_termin[16],
                                  $MOD_TE_termin[17],$MOD_TE_checkdate_von,$MOD_TE_checkdate_bis,
                                  $MOD_TE_termin[11],$MOD_TE_termin[12],$MOD_TE_termin[22]);
             if(count($MOD_TE_zdate_array) > 1)
                {
                for ($MOD_TE_durchlauf2=0; $MOD_TE_durchlauf2<count($MOD_TE_zdate_array); $MOD_TE_durchlauf2++)
                   {
                   $dateHelper = new DateTime($MOD_TE_zdate_array[$MOD_TE_durchlauf2]);
                   $MOD_TE_termin[date0_german] = $dateHelper->format("d.m.Y");
                   $MOD_TE_termin[date0_day] = $dateHelper->format("d");
                   $MOD_TE_termin[date0_month] = $dateHelper->format("m");
                   $MOD_TE_termin[date0_year] = $dateHelper->format("Y");
                   $MOD_TE_termin[date0_weekdaynum] = $dateHelper->format("w");
                   $MOD_TE_termin[date0_dayfull] = $days_short[$lang][$MOD_TE_termin[date0_weekdaynum]];
                   $MOD_TE_termin[date0_dayshort] = $days_full[$lang][$MOD_TE_termin[date0_weekdaynum]];
             

                   $MOD_TE_termin[show] = true;
                   if ( $MOD_TE_termin[18] == '1' &&  $MOD_TE_termin[1] < $MOD_TE_today ) $MOD_TE_termin[show] = false;

                   if($MOD_TE_amodus2 > -1)
                      {
                      if($MOD_TE_amodus2 == 0)
                         $MOD_TE_termin[show] = false;
                      elseif ($MOD_TE_termin[show])
                         $MOD_TE_amodus2 = $MOD_TE_amodus2-1;
                      }
                

                   }
                }      
          }
       
       


       $MOD_TE_highlight_check = ("$MOD_TE_termin[20]" == '1') ? "checked" : "";
        $MOD_TE_disabled = ($MOD_TE_ortsliste_array[0] != "") ? "disabled" : "";
        $MOD_TE_teaser_check = ("$MOD_TE_termin[7]" == 'yes') ? "checked" : "";
       
       
        // ab hier das Formular für die Editieransicht
       $MOD_TE_termin[startdate] = "CMS_DATE[1]";
       $MOD_TE_termin[enddate] = "CMS_DATE[2]";
       $MOD_TE_termin[text] = "CMS_HTML[8]";
       $MOD_TE_termin[linkstd] = "CMS_LINK[9]";
       $MOD_TE_termin[linkdesc] = "CMS_LINKEDITOR[9]";
       $MOD_TE_termin[cycle] = ($MOD_TE_termin[10] == "no"   ) ? false : true;
       $MOD_TE_termin[imagesrc] = "CMS_IMG[14]";
       $MOD_TE_termin[imageeditor] = "CMS_IMGEDITOR[14]";   
       
       $tpl = cSmartyFrontend::getInstance();
       //$tpl->assign('actionlink', "front_content.php?idcat=$idcat&idart=$idart&lang=$lang&client=$client&contenido=$contenido");
       $tpl->assign('errors', $errors);
       $tpl->assign('actionlink', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
       $tpl->assign('highlight_check', $MOD_TE_highlight_check);
       $tpl->assign('teaser_check', $MOD_TE_teaser_check);
       $tpl->assign('venuetype', $MOD_TE_disabled);
       $tpl->assign('fieldset_main', mi18n("fieldset_main"));
       $tpl->assign('fieldset_details', mi18n("fieldset_details"));
       $tpl->assign('fieldset_cycle', mi18n("fieldset_cycle"));
       $tpl->assign('label_title', mi18n("Titel"));
       $tpl->assign('label_startdate', mi18n("Startdatum"));
       $tpl->assign('label_enddate', mi18n("Enddatum"));
       $tpl->assign('label_venue', mi18n("Ort"));
       $tpl->assign('label_details', mi18n("Details"));
       $tpl->assign('label_teaser', mi18n("Im Teaser anzeigen?"));
       $tpl->assign('label_highlight', mi18n("In Terminliste hervorheben?"));
       $tpl->assign('label_category', mi18n("Kategorien auswählen"));
       $tpl->assign('label_cycle', mi18n("Termin Zyklus ab Startdatum"));
       $tpl->assign('label_everyXdays', mi18n("Falls x-ter Tag: Definiere Anzahl Tage"));
       $tpl->assign('label_everyXweekdays', mi18n("Zyklus Woche: jeden..."));
       $tpl->assign('label_everyXweeknums', mi18n("Zyklus Woche: jeden..."));
       $tpl->assign('label_adddates', mi18n("Zusätzliche Termine, kommagetrennt"));
       $tpl->assign('label_removedates', mi18n("Termine ausschließen, kommagetrennt"));
       $tpl->assign('label_formatyyyymmdd', mi18n("formatyyyymmdd"));
       $tpl->assign('label_save', mi18n("Speichern"));
       $tpl->assign('label_text', mi18n("Zusätzliche Info"));
       $tpl->assign('label_link', mi18n("Link setzen"));
       $tpl->assign('label_image', mi18n("Bild wählen"));
       $tpl->assign('info_misc', mi18n("infotext_misc"));
       $tpl->assign('categoryselect', createCheckbox($categories, $MOD_TE_termin[13], "categories", $lang, 160 ));
       $tpl->assign('cycle_weekdayselect', createCheckbox3($cycleweekdays, explode("," , $MOD_TE_termin[11]), "cycleweekdays", $lang, 105 ));
       $tpl->assign('cycle_weeknumselect', createCheckbox3($cycleweeknums, explode("," , $MOD_TE_termin[12]), "cycleweeknums", $lang, 50 ));
       $tpl->assign('termin', $MOD_TE_termin);
       $tpl->assign('cyclelist', $cycles);
       $tpl->assign('highlightlist', $highlightlist);
       $tpl->assign('venuelist', $MOD_TE_ortsliste_array);
       // elements needed for FEU display
       $tpl->assign('label_back', mi18n("Zurück zur Übersicht"));
       $tpl->assign('label_timeformat', mi18n("Uhr"));
        $tpl->assign('label_status', mi18n("Status"));
       
       if ($contenido) {
             $tpl->display($tpl_editor );
          } else {
             $tpl->display($tpl_fedisplay );   
       }
       



    ?>