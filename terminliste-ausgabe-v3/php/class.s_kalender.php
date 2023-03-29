<?php

/**
 * Class sKalender
 */
class sKalender
{
    /** @var array */
    private $date;

    /**
     * sKalender constructor.
     */
    public function __construct()
    {
        $this->date = getdate();
    }

    /**
     * @param $MOD_TL_tag
     * @param $MOD_TL_monat
     * @param $MOD_TL_jahr
     *
     * @return bool
     */
    public function setDate($MOD_TL_tag, $MOD_TL_monat, $MOD_TL_jahr)
    {
        // prüft, ob alle Werte übergeben wurden und schreibt diese dann ins Array
        if ($MOD_TL_tag != "" && $MOD_TL_monat != "" && $MOD_TL_jahr != "") {
            $this->date["mday"] = $MOD_TL_tag * 1;  // führende Nullen entfernen
            $this->date["mon"]  = $MOD_TL_monat * 1; // führende Nullen entfernen
            $this->date["year"] = $MOD_TL_jahr;

            return true;
        } else {
            return false;
        }
    }

    /**
     * @return false|string
     */
    public function printCalender()
    {
        // Globale Variablen definieren
        global $idart, $lang;
        global $days_short, $months_full;
        global $MOD;

        $output = "";

        if ($this->date == "") {
            return false;
        } else {
            $MOD_TL_datum = $this->date;
        }

        $MOD_TL_wochenbeginn = 2;    // Die Woche beginnt mit Montag, PHP beginnt mit Sonntag = 1

        $output .= '<div id="mod_sK">
<table>
<tr>';
        // Einen Monat zurück
        $MOD_TL_neues_datum = $this->getDateByMonth(-1);
        $MOD_TL_newdate     = date("Y", $MOD_TL_neues_datum) . '-' . date("n", $MOD_TL_neues_datum) . '-1';
        $output             .= '<td class="navl"><a href="front_content.php?idart=' . $idart . '&MOD_TL_newdate='
            . $MOD_TL_newdate . '" title="' . mi18n("vorheriger Monat") . '">&lt;&lt;</a></td>';

        // aktueller Monat
        $MOD_TL_neues_datum = $this->getDateByMonth(0);
        $MOD_TL_newdate     = date("Y", $MOD_TL_neues_datum) . '-' . date("n", $MOD_TL_neues_datum) . '-1';

        // Dropdownfeld aufbauen
        $MOD_TL_arry_years = count($MOD["array_down"]); // Anzahl der Jahre
        $MOD_TL_dropdown   = '<select name="MOD_TL_newdate" onchange="parent.frames.top.location.href = this.value">';
        $MOD_TL_dropdown   .= '<option value="' . $MOD_TL_newdate . '">' . $months_full[$lang][$MOD_TL_datum["mon"]]
            . '&nbsp;' . $MOD_TL_datum["year"] . '</option>';

        for ($MOD_TL_dyears = 0; $MOD_TL_dyears < $MOD_TL_arry_years; $MOD_TL_dyears++) {
            for ($MOD_TL_dmonth = 1; $MOD_TL_dmonth <= 12; $MOD_TL_dmonth++) {
                $MOD_TL_dropdown .= '<option value="front_content.php?subid=' . $subid . '&idart=' . $idart
                    . '&MOD_TL_newdate=' . $MOD["array_down"][$MOD_TL_dyears] . '-' . $MOD_TL_dmonth . '-1">'
                    . $months_full[$lang][$MOD_TL_dmonth] . '&nbsp;' . $MOD["array_down"][$MOD_TL_dyears] . '</option>';
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
            $MOD_TL_tag_nummer = date(
                    "w",
                    mktime(0, 0, 0, $MOD_TL_datum["mon"], $MOD_TL_tag_aktuell, $MOD_TL_datum["year"])
                ) + 1;
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

        $MOD_TL_tag_nummer =
            date("w", mktime(0, 0, 0, $MOD_TL_datum["mon"], $MOD_TL_tag_aktuell, $MOD_TL_datum["year"]));

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

    /**
     * @param $MOD_TL_neuer_monat
     *
     * @return false|int
     */
    private function getDateByMonth($MOD_TL_neuer_monat)
    {
        return mktime(
            0,
            0,
            0,
            $this->date["mon"] + $MOD_TL_neuer_monat,
            $this->date["mday"],
            $this->date["year"]
        );
    }

    /**
     * @param $MOD_TL_datum_check
     *
     * @return false
     */
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

    /**
     * @param $MOD_TL_datum_check
     *
     * @return bool
     */
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

    /**
     * Tag mit reservierten Terminen
     *
     * @param $MOD_TL_datum_check
     *
     * @return bool
     */
    private function checkStatus1($MOD_TL_datum_check)
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

    /**
     * @param $MOD_TL_datum_check
     *
     * @return boolTag mit gebuchten Terminen
     */
    private function checkStatus2($MOD_TL_datum_check)
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

    /**
     * @param $MOD_TL_tag
     *
     * @return string
     */
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
            $MOD_TL_style = "wochentag";
            // Tag mit reservierten Terminen
            if ($this->checkStatus1($MOD_TL_datum_aktuell)) {
                $MOD_TL_termin_ok = true;
                $MOD_TL_style     = "tangiert";
            }
            // Tag mit gebuchten Terminen
            if ($this->checkStatus2($MOD_TL_datum_aktuell)) {
                $MOD_TL_termin_ok = true;
                $MOD_TL_style     = "belegt";
            }
        }

        if ($MOD_TL_termin_ok) {
            if ($MOD["linkyn"]) {
                $MOD_TL_val =
                    '<td class="' . $MOD_TL_style . '"><a class="tag" href="front_content.php?idart=' . $MOD["idart"]
                    . '&MOD_TL_newdate=' . $MOD_TL_datum_aktuell . '&MOD_TL_show=t" title="' . mi18n("Termine für den")
                    . ' ' . $MOD_TL_datum_title . ' ' . mi18n("anzeigen") . '.">' . $MOD_TL_tag . '</a></td>';
            } else {
                if ($MOD["anzeigemodus"]) {
                    $MOD_TL_val = '<td class="' . $MOD_TL_style . '"><a class="tag" title="' . mi18n("Für den") . ' '
                        . $MOD_TL_datum_title . ' ' . mi18n("existieren Termine") . '.">' . $MOD_TL_tag . '</a></td>';
                } else {
                    $MOD_TL_val = '<td class="' . $MOD_TL_style . '"><a class="tag" title="' . mi18n("gebucht") . '">'
                        . $MOD_TL_tag . '</a></td>';
                }
            }
        } else {
            if ($MOD["anzeigemodus"]) {
                $MOD_TL_val = '<td class="' . $MOD_TL_style . '"><a class="tag" title="' . mi18n("Für den") . ' '
                    . $MOD_TL_datum_title . ' ' . mi18n("existieren keine Termine.") . '">' . $MOD_TL_tag . '</a></td>';
            } else {
                $MOD_TL_val =
                    '<td class="' . $MOD_TL_style . '"><a class="tag" title="' . mi18n("frei") . '">' . $MOD_TL_tag
                    . '</a></td>';
            }
        }

        return $MOD_TL_val;
    }
}
