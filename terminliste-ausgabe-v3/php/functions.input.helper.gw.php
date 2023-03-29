<?php
/**
*  Various helping functions for the Input area of modules
*  Author:      B. Behrens, HerrB
*  Version:     1.4
*  Modified by: T. Gomes, funomat
*/

defined('CON_FRAMEWORK') || die('Illegal call: Missing framework initialization - request aborted.');

function fncBuildCategorySelect($sName, $sSelCat = '', $sLevel = 0, $sOnChange = '', $sSize = '1', $sType = '', $bWithArticle = false, $sSelCatArt = '', $sDisabled = '', $sCatDisabled = false) {
   global $cfg, $client, $lang, $idcat;

   $db  = cRegistry::getDb();
   $db2 = cRegistry::getDb();

   if ($sType) {
      $sType = " multiple";
   }
   if (!is_numeric($sSize)) {
      $sSize = "1";
   }

   $aSelCat    = explode(",", $sSelCat);
   $aSelCatArt = explode(",", $sSelCatArt);

   $sql  = "SELECT a.idcat AS idcat, b.name AS name, b.visible AS visible, b.public AS public, c.level AS level FROM ".
           $cfg["tab"]["cat"]." AS a, ".$cfg["tab"]["cat_lang"]." AS b, ".$cfg["tab"]["cat_tree"]." AS c ".
           "WHERE a.idclient = '".$client."' AND b.idlang = '".$lang."' AND b.idcat = a.idcat AND c.idcat = a.idcat ";
   if ($sLevel > 0) {
      $sql .= "AND c.level < '".$sLevel."' ";
   }
   $sql .= "ORDER BY c.idtree";

   $db->query($sql);

   if ($db->numRows() == 0 || $sDisabled) {
      $sDisabled = ' disabled="disabled"';
   } else {
      $sDisabled = '';
   }
   
   if ($sCatDisabled) {
      $sCatDisabled = ' disabled="disabled"';
   } else {
      $sCatDisabled = '';
   }

   $sHtml  = '<select name="'.$sName.'"'.$sType.' size="'.$sSize.'" onchange="'.$sOnChange.'"'.$sDisabled.'>'."\n";
   $sHtml .= '  <option value="">'.i18n("Please choose").'</option>'."\n";

   while ($db->nextRecord()) {
      $sSpaces = "&nbsp;&nbsp;";

      for ($i = 0; $i < $db->f("level"); $i ++) {
         $sSpaces .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      }

      $sSelected = "";
      $sSearchCat = 'cat_'.$db->f("idcat");

      if (in_array($sSearchCat, $aSelCat)) {
         $sSelected = ' selected="selected"';
      }
      $sStyle = "";
      if ($db->f("visible") == 0 || $db->f("public") == 0) {
         $sStyle = 'color: #666666;';
      }

      $sHtml .= '<option'.$sCatDisabled.' value="cat_'.$db->f("idcat").'" style="background-color:#EFEFEF;'.$sStyle.'"'.$sSelected.'>'.$sSpaces.">".urldecode($db->f("name")).'</option>'."\n";

      if ($bWithArticle) { // edit by funomat -> bugfix: change $bWithArticles to $bWithArticle
         $sql2 = "SELECT a.title AS title, b.idcatart AS idcatart, a.online AS online FROM
                 ".$cfg["tab"]["art_lang"]." AS a,  ".$cfg["tab"]["cat_art"]." AS b
                 WHERE b.idcat = '".$db->f("idcat")."' AND a.idart = b.idart AND
                 a.idlang = '".$lang."' ORDER BY a.title";
         $db2->query($sql2);

         while ($db2->nextRecord()) {
            $sSelected = "";
            $sSearchCatArt = 'art_'.$db2->f("idcatart");

            if (in_array($sSearchCatArt, $aSelCatArt)) {
               $sSelected = ' selected="selected"';
            }
            $sStyle = "";
            if ($db2->f("online") == 0) {
               $sStyle = 'color: #666666;';
            }
            $sHtml .= '<option value="art_'.$db2->f("idcatart").'" style="background-color:#fff;'.$sStyle.'"'.$sSelected.'>'.$sSpaces.
                      '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.substr(urldecode($db2->f("title")), 0, 32).'</option>'."\n";
         }
      }
   }

   $sHtml .= '</select>'."\n";

   unset ($db2);
   unset ($sql);
   unset ($aCategories);
   unset ($sSpaces);
   unset ($sSelected);
   unset ($sSearchCat);
   unset ($sSearchCatArt);
   unset ($sStyle);

   return $sHtml;
}

function fncBuildArticleSelect($sName, $sIDCat, $sSelCatArt, $sOnChange = '', $sDisabled = '') {
   global $cfg, $lang;

   $db2 = cRegistry::getDb();

   if (is_numeric($sIDCat) && $sIDCat > 0) {
      $sql  = "SELECT a.title AS title, b.idcatart AS idcatart, a.online AS online ";
      $sql .= "FROM ".$cfg["tab"]["art_lang"]." AS a, ".$cfg["tab"]["cat_art"]." AS b ";
      $sql .= "WHERE a.online = '1' AND b.idcat = '".$sIDCat."' AND a.idart = b.idart AND a.idlang = '".$lang."'";

      $db2->query($sql);

      if ($db2->numRows() == 0 || $sDisabled) {
         $sDisabled = ' disabled="disabled"';
      } else {
         $sDisabled = '';
      }

      $sHtml  = '<select name="'.$sName.'" onchange="'.$sOnChange.'"'.$sDisabled.'>'."\n";
      $sHtml .= '   <option value="" selected>'.i18n("Please choose").'</option>'."\n";

      while ($db2->nextRecord()) {
         $sSelected = "";
         $sSearchCatArt = 'art_'.$db2->f("idcatart");
         if ($sSelCatArt == $sSearchCatArt) {
            $sSelected = ' selected="selected"';
         }
         $sStyle = "";
         if ($db2->f("online") == 0) {
            $sStyle = 'color: #666666;';
         }

         $sHtml .= '<option value="'.$db2->f("idcatart").'" style="'.$sStyle.'"'.$sSelected.'>&nbsp;'.substr(urldecode($db2->f('title')), 0, 32).'</option>'."\n";
      }
   } else {
      $sHtml  = '<select name="'.$sName.'" disabled="disabled">'."\n";
      $sHtml .= '   <option value="" selected>'.i18n("Please choose").'</option>'."\n";
   }
   $sHtml .= '</select>'."\n";

   unset ($db2);
   unset ($sSelected);
   unset ($sSearchCatArt);
   unset ($sStyle);

   return $sHtml;
}

function fncBuildTypeSelect($sName, $lIDCatArt, $sValue, $sTypeRange = '', $sOnChange = '', $sDisabled = '') {
   global $cfg, $lang;

   $db2 = cRegistry::getDb();

   $lIDCatArt = str_replace('art_', '', $lIDCatArt);
   
   if (is_numeric($lIDCatArt) && $lIDCatArt > 0) {
      $sql = "SELECT
           a.typeid AS typeid,
           a.value AS value,
           a.idtype AS idtype,
           d.type AS type,
           d.description AS description
              FROM
           ".$cfg["tab"]["content"]." AS a,
           ".$cfg["tab"]["art_lang"]." AS b,
           ".$cfg["tab"]["cat_art"]." AS c,
           ".$cfg["tab"]["type"]." AS d
              WHERE
           a.idtype    = d.idtype AND
           a.idartlang = b.idartlang AND
           b.idart     = c.idart AND
           b.idlang    = '".$lang."' AND ";
      if ($sTypeRange != "") {
         $sql .= "a.idtype IN (".$sTypeRange.") AND ";
      }
      $sql .= "     c.idcatart = '".$lIDCatArt."'
              ORDER BY a.idtype, a.typeid";

      $db2->query($sql);

      if ($db2->numRows() == 0 || $sDisabled) {
         $sDisabled = ' disabled="disabled"';
      } else {
         $sDisabled = '';
      }

      $html  = '<select name="'.$sName.'" onchange="'.$sOnChange.'"'.$sDisabled.'>'."\n";
      $html .= '   <option value="" selected>'.i18n("Please choose").'</option>'."\n";

      while ($db2->nextRecord()) {
         $sTypeIdentifier = "tblDataidtype".$db2->f('idtype')."tblDatatypeid".$db2->f('typeid');
         $sContent = ($db2->f("value") != '') ? substr(strip_tags(urldecode($db2->f("value"))), 0, 20).'...' : '';
         $sContent = $db2->f('type')."[".$db2->f('typeid')."]: ".$sContent;
         $sDescription = i18n($db2->f("description"));

         if ($sValue != $sTypeIdentifier) {
            $html .= '<option value="'.$sTypeIdentifier.'">&nbsp;'.$sContent.'</option>'."\n";
         } else {
            $html .= '<option selected="selected" value="'.$sTypeIdentifier.'">&nbsp;'.$sContent.'</option>'."\n";
         }
      }
   } else {
      $html  = '<select name="'.$sName.'" disabled="disabled">'."\n";
      $html .= '   <option value="" selected>'.i18n("Please choose").'</option>'."\n";
   }
   $html .= '</select>'."\n";

   unset ($db2);
   unset ($sTypeIdentifier);
   unset ($sContent);
   unset ($sDescription);

   return $html;
}
?>