<?php 
/*********************************************** 
* Funktionssammlung für Terminliste
* für Terminmodule Version 2.5.2
* 
* Name        :     functions.terminliste.php
* Version     :     1.1
* Author      :     schlaucher 
* Created     :     11-01-2008
* Modified    :     31-01-2008
************************************************/ 

// function dateout($ablauf,$ablauftage)
// gibt als Rückgabewert das Datum des ältesten Termins, der angezeigt werden darf
//
function dateout($ablauf,$ablauftage)
    {
    // Anzeige von ermitteln
    switch($ablauf)
        {
        case "keine": // keine abgelaufenen Termine
            $date_out = date("Y-m-d");
            break;
        case "alle": // alle abgelaufenen Termine
            $date_out = '1970-01-01';
            break;
        case "woche": // aktuelle Woche
            $wtag_aktuell = getdate(mktime());
            $wtag_diff = $wtag_aktuell["wday"]-1;
            $date_out = date("Y-m-d");
            if ($wtag_diff >= 1)
                $date_out = date("Y-m-d", mktime(0,0,0, date("m"), date("d")-$wtag_diff, date("Y")));
            if ($wtag_diff < 0)
                $date_out = date("Y-m-d", mktime(0,0,0, date("m"), date("d")-6, date("Y")));
            break;
        case "monat": // aktueller Monat
            $date_out = date("Y-m-d", mktime(0,0,0, date("m"), 1, date("Y")));
            break;
        case "jahr": // aktuelles Jahr
            $date_out = date("Y-m-d", mktime(0,0,0, 1, 1, date("Y")));
            break;
        case "lastdays": // Anzahl Tage
            if ((!$ablauftage) || (!is_numeric($ablauftage)))
                    $ablauftage = 0;
                    $date_out = date("Y-m-d", mktime(0,0,0, date("m"), date("d")-$ablauftage, date("Y")));
            break;
        }
    return $date_out;
    }
    
// function datein($bis,$plustage,$anzahltage,$bistage)
// gibt als Rückgabewert das Datum des neuesten Termins, der angezeigt werden darf
//
function datein($bis,$plustage,$anzahltage,$bistage)
    {
    // Anzeige bis ermitteln
    $date_in = '9999-99-99'; // alle Tage
    if ((!$plustage) || (!is_numeric($plustage)))
        $plustage = 0;
    switch($bis)
        {
        case "keine": // keine aktuellen Termine
            $date_in = date("Y-m-d");
            break;
        case "woche": // aktuelle Woche
            $wtag_aktuell = getdate(mktime());
            $wtag_diff = 7-$wtag_aktuell["wday"];
            $date_out = date("Y-m-d");
            if ($wtag_diff == 6)
                $date_in = date("Y-m-d", mktime(0,0,0, date("m"), date("d")+6+$plustage, date("Y")));
            elseif ($wtag_diff == 7)
                $date_in = date("Y-m-d", mktime(0,0,0, date("m"), date("d")+$plustage, date("Y")));
            else
                $date_in = date("Y-m-d", mktime(0,0,0, date("m"), date("d")+$wtag_diff+$plustage, date("Y")));
            break;
        case "monat": // aktueller Monat
            $date_in = date("Y-m-d", mktime(0,0,0, date("m")+1, 0+$plustage, date("Y")));
            break;
        case "jahr": // aktuelles Jahr
            $date_in = date("Y-m-d", mktime(0,0,0, 13, 0+$plustage, date("Y")));
            break;
        case "tage": // anzahl Tage
            if ((!$anzahltage) || (!is_numeric($anzahltage)))
                $anzahltage = 0;
            $date_in = date("Y-m-d", mktime(0,0,0, date("m"), date("d")+$anzahltage-1, date("Y")));
            break;
        case "datum": // von - bis
            if($bistage)
                $date_in = $bistage;
            break;
        }
    return $date_in;
    }

//function makedatearray($datum1,$datum2,$option,$xtag,$aliste,$ablaufdatum,$anzeigedatum,$wtagezyklus,$wtagemon)
// gibt als Rückgabewert ein Array mit allen gültigen Terminen eines Zyklus zurück
function makedatearray($datum1,$datum2,$option,$xtag,$aliste,$ablaufdatum,$anzeigedatum,$wtagezyklus,$wtagemon,$zutermine)
    {
		
	//print "check".$datum1.$datum2."opt".$option."xtag".$xtag."aliste".$aliste."ablauf".$ablaufdatum."anzeige".$anzeigedatum."wtagez".$wtagezyklus."wtagem".$wtagemon."zutermine".$zutermine;
    $datum_array_neu = array();
    $datum1_array = explode("-",$datum1);
    $datum2_array = explode("-",$datum2);
    $aliste_array = explode(",",$aliste);
    $zutermine_array = explode(",",$zutermine);
    $tage_kurz = array('So','Mo','Di','Mi','Do','Fr','Sa');


    if ($option == "tag" || $option == "xtag" || $option == "woche" || $option == "14tag")
        {
        switch($option)
            {
            case "tag": // täglich
                $date_diff = 86400;
                $date_nextday = 1;
                break;
            case "xtag": // jeden x-ten Tag
                if ((!$xtag) || $xtag == 0 || (!is_numeric($xtag)))
                    $xtag = 1;
                $date_diff = 86400*$xtag;
                $date_nextday = $xtag;
                break;
            case "woche": // wöchentich
                $date_diff = 604800;
                $date_nextday = 7;
                break;
            case "14tag": // 14tägig
                $date_diff = 1209600;
                $date_nextday = 14;
                break;
            }
    
        $anzahl = (mktime('0','0','0',$datum2_array[1],$datum2_array[2],$datum2_array[0])-mktime('0','0','0',$datum1_array[1],$datum1_array[2],$datum1_array[0]))/$date_diff;
        $date_aktuell = mktime('0','0','0',$datum1_array[1],$datum1_array[2],$datum1_array[0]);
        
        $zaehler = 0;
        for($durchlauf = 0; $durchlauf<=$anzahl; $durchlauf++)
            {
            if(date("Y-m-d",$date_aktuell) >= $ablaufdatum && date("Y-m-d",$date_aktuell) <= $anzeigedatum)
                {
                if (!in_array(date("Y-m-d",$date_aktuell),$aliste_array))
                    {
                    $datum_array_neu[$zaehler] = date("Y-m-d",$date_aktuell);
                    $zaehler++;
                    }
                }
            $date_aktuell = mktime('0','0','0',$datum1_array[1],$datum1_array[2]+(($durchlauf+1)*$date_nextday),$datum1_array[0]);
            }
        }
    
    if ($option == "monat" || $option == "jahr") // monatlich und jährlich
        {
        $datum_start = mktime('0','0','0',$datum1_array[1],$datum1_array[2],$datum1_array[0]);
        $datum_stop = mktime('0','0','0',$datum2_array[1],$datum2_array[2],$datum2_array[0]);
        
        $zaehler = 0;
        $durchlauf = 0;
        while ($datum_start <= $datum_stop)
            {
            if(date("Y-m-d",$datum_start) >= $ablaufdatum && date("Y-m-d",$datum_start) <= $anzeigedatum)
                {
                if (!in_array(date("Y-m-d",$datum_start),$aliste_array))
                    {
                    $datum_array_neu[$zaehler] = date("Y-m-d",$datum_start);
                    $zaehler++;
                    }
                }
            if($option == "monat")
                $datum_start = mktime('0','0','0',$datum1_array[1]+$durchlauf+1,$datum1_array[2],$datum1_array[0]);
            if($option == "jahr")
                $datum_start = mktime('0','0','0',$datum1_array[1],$datum1_array[2],$datum1_array[0]+$durchlauf+1);
            $durchlauf++;
            }
        }

    if ($option == "wtage") // jeder 1., 2., 3., ...
        {
        $date_diff = 86400;
        $anzahl = (mktime('0','0','0',$datum2_array[1],$datum2_array[2],$datum2_array[0])-mktime('0','0','0',$datum1_array[1],$datum1_array[2],$datum1_array[0]))/$date_diff;
		//print "<br>anzahl".$anzahl;
                
         // Eingabefeld der Tage prüfen
        if (stristr($wtagezyklus, ','))  // Werte wurden mit Komma getrennt
            $wtage_array = explode(",",$wtagezyklus);
        elseif ($wtagezyklus != '') // nur ein Tag
            $wtage_array[0] = $wtagezyklus;
        else
            $wtage_array[0] = '';

        $date_aktuell = mktime('0','0','0',$datum1_array[1],$datum1_array[2],$datum1_array[0]);
        $zaehler2 = 0;
                
        for ($zaehler=0; $zaehler<=$anzahl; $zaehler++)
            {
            $wtag_check = getdate($date_aktuell); 
            $mtag_check = $wtag_check[mday]; // Numerischer Tag des Monats ermitteln
            $wtag_check = $tage_kurz[$wtag_check[wday]]; // Wochentagabkürzung des aktuellen Wochentages ermitteln
            $wtag_ok = true;
            //print $mtag_check.$wtag_check;
			
            if (in_array($wtag_check, $wtage_array)) 
                {
                if (strlen($wtagemon) > 1 ) // Prüfung auf 1.,2. ...
                    {
                    $wtag_ok = false;
                    if (stristr($wtagemon, '1') && !$wtag_ok) 
                        {
                        if ( $mtag_check >= 1 && $mtag_check <=7 )
                            $wtag_ok = true;
                        }
                    if (stristr($wtagemon, '2') && !$wtag_ok) 
                        {
                        if ( $mtag_check >= 8 && $mtag_check <=14 )
                            $wtag_ok = true;
                        }
                    if (stristr($wtagemon, '3') && !$wtag_ok) 
                        {
                        if ( $mtag_check >= 15 && $mtag_check <=21 )
                            $wtag_ok = true;
                        }
                    if (stristr($wtagemon, '4') && !$wtag_ok) 
                        {
                        if ( $mtag_check >= 22 && $mtag_check <=28 )
                            $wtag_ok = true;
                        }
                    if (stristr($wtagemon, '5') && !$wtag_ok) 
                        {
                        if ( $mtag_check >= 29 && $mtag_check <=31 )
                            $wtag_ok = true;
                        }
                    }
                        
                if ($wtag_ok)
                    {
                    if(date("Y-m-d",$date_aktuell) >= $ablaufdatum && date("Y-m-d",$date_aktuell) <= $anzeigedatum)
                        {
                        if (!in_array(date("Y-m-d",$date_aktuell),$aliste_array))
                            {
                            $datum_array_neu[$zaehler2] = date("Y-m-d",$date_aktuell);
                            $zaehler2++;
                            }
                        }
                    }
                }
            $date_aktuell = mktime(0,0,0,$datum1_array[1],$datum1_array[2]+$zaehler+1,$datum1_array[0]);
            }
        }
    
    if(count($zutermine_array) > 0) // wenn zusätzliche Termine eingegeben 
        {
        $start = count($datum_array_neu);
        $termine = count($zutermine_array);
        for($zaehler = 0; $zaehler < $termine; $zaehler++)
            {
            if (!in_array($zutermine_array[$zaehler],$aliste_array))
                $datum_array_neu[$start+$zaehler] = $zutermine_array[$zaehler];
            }
        array_multisort($datum_array_neu, SORT_ASC, SORT_STRING);
        }
    return $datum_array_neu;
    }

?>