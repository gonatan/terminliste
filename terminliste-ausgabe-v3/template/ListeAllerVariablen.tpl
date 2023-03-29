pro Termin können aufgerufen werden:

$termin.datum1 - das Startdatum in yyyy-mm-dd
$termin.datum2 - das Enddatum in yyyy-mm-dd
$termin.zeit1 - Startzeit in H:i:s
$termin.zeit2 - Endzeit in H:i:s
$termin.idart - idart des Event-Artikels
$termin.ort - Ort
$termin.titel - Titel
$termin.teaser - true/false
$termin.text - Text aus dem CMS-Container
$termin.link - Linkziel aus dem CMS-Container
$termin.linkframe - blank/self
$termin.linkdesc - Beschreibung wie im CMS-Container eingegeben
$termin.zyklus - Art des Zyklus, sonst false
$termin.wtagezyklus - Art des Wochentagezyklus
$termin.wtagemon - Zyklus: Welche Wochentage im Monat
$termin.kategorie - zugeordnete Terminkategorien
$termin.image - Bildname
$termin.imageid - Bild-ID der upload-Tabelle
$termin.imagedir - Bildpfad (ohne Bildnamen)
$termin.imagefullpath - Bildpfad mit Bildname, aber noch ohne "upload"
$termin.medianame - Medienname (Dateiverwaltung)
$termin.description - Beschreibung (Dateiverwaltung)
$termin.copyright - Copyright (Dateiverwaltung)
$termin.keywords - Schlüsselwörter (Dateiverwaltung)
$termin.internat_description - Interne Beschreibung (Dateiverwaltung)
$termin.xtag - Zyklus x-ter-Tag
$termin.aliste - String mit Ausschlussterminen
$termin.highlight - class-Name, falls Termin besonders hervorgehoben werden soll
$termin.zutermine - String mit den hinzuzufügenden Terminen
$termin.oneday - true, wenn sich der Termin über nur einen Tag erstreckt bzw. kein Enddatum gesetzt ist; sonst false
$termin.groupeddate - true, wenn der Termin das gleiche Startdatum wie der Vorgängertermin hat

Darüber hinaus werden für Datum und Zeit noch einige zusätzliche Elemente generiert pro  Start-/Enddatum, "1" für Enddatum durch "2" ersetzen:
$termin.date1_lang - für Mandantensprache korrekte Variante des kompletten Datums gemäß Datumsformat im Administrationsbereich
$termin.date1_month - Monat, numerisch, zweistellig
$termin.date1_monthnum - Monat, numerisch, ein- oder zweistellig
$termin.date1_monthshort - abgekürzter Monatsname
$termin.date1_monthfull - voller Monatsname
$termin.date1_yearshort - Jahreszahl zweistellig
$termin.date1_yearfull - Jahreszahl vierstellig
$termin.date1_day - Tag, numerisch, zweistellig
$termin.date1_daynum - Tag, numerisch, ein- oder zweistellig
$termin.date1_dayshort - Name des Tages, abgekürzt
$termin.date1_dayfull - Name des Tages, volle Länge

Aus dem Modul können fast sämtliche Parameter aufgerufen werden, die für die jeweilige Liste zur Berechnung herangezogen werden. 
Bitte hierfür in den Modulcode schauen, die Variablen sind im Format
$MOD[x]


