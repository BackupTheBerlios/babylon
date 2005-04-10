<?php
// StilName = Stern

/* Copyright 2003, 2004, 2005 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function css_setzen ()
{
  echo '<link href="stil/stern.css" rel="stylesheet" type="text/css">';
}

function zeichne_forum ($param)
{
  $Erster = $param['Erster'];
  $ForumId = $param['ForumId'];
  $NumBeitraege = $param['NumBeitraege'];
  $Titel = $param['Titel'];
  $Inhalt = $param['Inhalt'];
  setlocale (LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
  $datum = $param['StempelLetzter'] ? strftime ("%d.%b.%Y", $param['StempelLetzter']) : '';
  $zeit = $param['StempelLetzter'] ? date ("H.i:s", $param['StempelLetzter']) : '';

  if (! strlen ($datum))
    $datum = ' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;';

  echo "<tr><td>
  <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">
    <tr>
      <td><img src=\"stil/dummy.png\" height=\"15\" border=\"0\"></td>
      <td rowspan=2><img src=\"stil/stern01.png\"></td>
      <td rowspan=2><img src=\"stil/stern02.png\"></td>
      <td rowspan=2 style=\"background-image:url(stil/stern03.png)\" class=\"forum_kopf\"><nobr>$Titel</nobr></td>
      <td rowspan=2><img src=\"stil/stern04.png\"></td>
      <td rowspan=2 style=\"background-image:url(stil/stern05.png)\" width=\"100%\"></td>
      <td rowspan=2><img src=\"stil/stern06.png\"></td>
      <td rowspan=2 style=\"background-image:url(stil/stern07.png)\" class=\"forum_kopf\"><nobr>$datum $zeit</nobr></td>
      <td rowspan=2><img src=\"stil/stern08.png\"></td>
      <td rowspan=2><img src=\"stil/stern09.png\"></td>
      <td><img src=\"stil/dummy.png\"></td>
    </tr>
    <tr>
      <td><img src=\"stil/stern10.png\"></td>
      <td><img src=\"stil/stern1a.png\"></td>
    </tr>
    <tr>
      <td><img src=\"stil/stern20.png\"></td>
      <td colspan=6 style=\"background-image:url(stil/stern25.png)\" class=\"forum_rumpf\">";
  
  if ($ForumId == -1)
    echo "      <a href=\"posteingang.php\" class=\"forum_rumpf\"><nobr>$Inhalt</nobr></td>
      <td colspan=3 style=\"background-image:url(stil/stern25.png)\" class=\"forum_rumpf\" align=\"right\"><nobr>Neue Beitr&auml;ge: $NumBeitraege</nobr>";
  else
    echo "      <a href=\"themen.php?fid=$ForumId&tid=-1\" class=\"forum_rumpf\"><nobr>$Inhalt</nobr></td>
      <td colspan=3 style=\"background-image:url(stil/stern25.png)\" class=\"forum_rumpf\" align=\"right\"><nobr>Themen: $NumBeitraege</nobr>";
      
  echo "    </td>
      <td><img src=\"stil/stern2a.png\"></td>
    </tr>
  </table>
  </td></tr><tr><td>&nbsp;</td></tr>";   
}

function zeichne_thema ($param)
{
  $Erster = $param['Erster'];
  $ForumId = $param['$ForumId'];
  $ThemaId = $param['ThemaId'];
  $Autor = $param['Autor'];
  $AutorURL = rawurlencode ($Autor);
  $AutorLetzter = $param['AutorLetzter'];
  $AutorLetzterURL = rawurlencode ($AutorLetzter);
  $StempelLetzter = $param['StempelLetzter'];
  $Titel = $param['Titel'];
  $NumBeitraege = $param['NumBeitraege'];
  $NumGelesen = $param['NumGelesen'];
  $BaumZeigen = $param['BaumZeigen'];

  setlocale (LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
  $datum = strftime ("%d.%m.%Y", $StempelLetzter);
  $zeit = date ("H.i", $StempelLetzter);
  $aw = $NumBeitraege - 1;

  if ($BaumZeigen)
    $sprung = 'beitrags-baum';
  else
    $sprung = 'beitraege';
 
  echo "<tr><td>
  <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">
    <tr>
      <td><img src=\"stil/dummy.png\" height=\"15\" border=\"0\"></td>
      <td rowspan=2><img src=\"stil/stern01.png\"></td>
      <td rowspan=2><img src=\"stil/stern02.png\"></td>
      <!--td rowspan=2><img src=\"stil/stern03.png\"></td-->
      <td rowspan=2 style=\"background-image:url(stil/stern03.png)\" class=\"forum_kopf\"><nobr><a href=\"mitglieder-profil.php?alias=$AutorURL\">$Autor</a></nobr></td>
      <td rowspan=2><img src=\"stil/stern04.png\"></td>
      <td rowspan=2 style=\"background-image:url(stil/stern05.png)\" width=\"100%\"></td>
      <td rowspan=2><img src=\"stil/stern06.png\"></td>
      <td rowspan=2 style=\"background-image:url(stil/stern07.png)\" class=\"forum_kopf\"><nobr>$datum $zeit</nobr></td>
      <td rowspan=2><img src=\"stil/stern08.png\"></td>
      <td rowspan=2><img src=\"stil/stern09.png\"></td>
      <td><img src=\"stil/dummy.png\"></td>
    </tr>
    <tr>
      <td><img src=\"stil/stern10.png\"></td>
      <td><img src=\"stil/stern1a.png\"></td>
    </tr>
    <tr>
      <td><img src=\"stil/stern20.png\"></td>
      <td colspan=6 style=\"background-image:url(stil/stern25.png)\" class=\"forum_rumpf\"><a href=\"$sprung.php?fid=$ForumId&tid=$ThemaId&bid=-1&sid=-1\" class=\"forum_rumpf\"><nobr>$Titel</nobr></td>
      <td colspan=3 style=\"background-image:url(stil/stern25.png)\" class=\"forum_rumpf\" align=\"right\">Antworten: $aw</td>
      
      <td><img src=\"stil/stern2a.png\"></td>
    </tr>
  </table>
  </td></tr><tr><td>&nbsp;</td></tr>";
}

function zeichne_baum ()
{
  
}

function zeichne_beitrag ($param)
{
  $Erster = $param['Erster'];
  $ForumId = $param['ForumId'];
  $BeitragId = $param['BeitragId'];
  $Autor = $param['Autor'];
  $AutorURL = rawurlencode ($Autor);
  $StempelLetzter = $param['StempelLetzter'];
  $Thema = $param['Thema'];
  $Inhalt = $param['Inhalt'];
  $Egl = $param['Egl'];
  $Atavar = $param['Atavar'];

  setlocale (LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
  $datum = strftime ("%d.%m.%Y", $StempelLetzter);
  $zeit = date ("H.i", $StempelLetzter);

  if ($Erster)
    echo "<tr>
      <td align=\"center\"><h2>$Thema</h2></td>
    </tr>";

  echo "<tr><td>
  <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">
    <tr>
      <td><img src=\"stil/dummy.png\" height=\"15\" border=\"0\"></td>
      <td rowspan=2><img src=\"stil/stern01.png\"></td>
      <td rowspan=2><img src=\"stil/stern02.png\"></td>
      <!--td rowspan=2><img src=\"stil/stern03.png\"></td-->
      <td rowspan=2 style=\"background-image:url(stil/stern03.png)\" class=\"forum_kopf\"><nobr><a href=\"mitglieder-profil.php?alias=$AutorURL\">$Autor</a></nobr></td>
      <td rowspan=2><img src=\"stil/stern04.png\"></td>
      <td rowspan=2 style=\"background-image:url(stil/stern05.png)\" width=\"100%\"></td>
      <td rowspan=2><img src=\"stil/stern06.png\"></td>
      <td rowspan=2 style=\"background-image:url(stil/stern07.png)\" class=\"forum_kopf\"><nobr>$datum $zeit</nobr></td>
      <td rowspan=2><img src=\"stil/stern08.png\"></td>
      <td rowspan=2><img src=\"stil/stern09.png\"></td>
      <td><img src=\"stil/dummy.png\"></td>
    </tr>
    <tr>
      <td><img src=\"stil/stern10.png\"></td>
      <td><img src=\"stil/stern1a.png\"></td>
    </tr>
    <tr>
      <td colspan=2><img src=\"stil/stern20-2.png\"></td>
      <td colspan=7 style=\"background-image:url(stil/stern25-2.png)\"></td>
      <td colspan=2><img src=\"stil/stern2a-2.png\"></td>
    </tr>
    <tr>
      <td colspan=2 style=\"background-image:url(stil/stern30-2.png)\"></td>
      
      
      <td colspan=7>
        <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"  width=\"100%\">
          <tr>\n";
    if ($Atavar > -1)
      echo"     <td bgcolor=\"#212248\" valign=\"top\">
                  <img src=\"atavar-ausgeben.php?atavar=$Atavar\" border=\"2\">
                </td>";
    echo "            <td bgcolor=\"#212248\" width=\"100%\" valign=\"top\">$Inhalt</td>
          </tr>
        </table>
      </td>
      
      <td colspan=2 style=\"background-image:url(stil/stern3a-2.png)\"></td>
    </tr>
  </table>
  <table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">
    <tr>
      <td><img src=\"stil/stern40-2.png\"></td>
      </td><td style=\"background-image:url(stil/stern43-2.png)\">";
      
//            <td colspan=7 bgcolor=\"#212248\">$Inhalt</td>
      
    if ($erster)
      echo "<font size=\"-1\"><nobr><input type=\"radio\" name=\"eltern\" value=$BeitragId checked>Antworten auf diesen Beitrag</input></nobr>";
    else
      echo "<font size=\"-1\"><nobr><input type=\"radio\" name=\"eltern\" value=$BeitragId>Antworten auf diesen Beitrag</input></nobr>";
     
//  if ($K_Egl)
  echo "<td><img src=\"stil/stern44-2.png\"></td>
     </td><td style=\"background-image:url(stil/stern45-2.png)\" width=\"100%\">
      </td><td colspan=5><img src=\"stil/stern4a-2.png\"></td>
    </tr>
    
  </table>
  </td></tr><tr><td><p></td></tr>";


  // Die Antwort Zeile
/**/
//    echo "<td><font size=\"-1\"><a href=\"beitraege.php?fid=$ForumId&tid=$ThemaId&sid=$StrangId&zitat=$BeitragId\">Diesen Beitrag zitieren</a></td>";
/*    echo "<td><font size=\"-1\">weitere Antworten:</td>";
    echo "</font></tr><tr><td><br></td></tr>";
  }*/
}
?>
