<?PHP;
// StilName = Standart

/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function css_setzen ()
{
  echo '    <link href="stil/std.css" rel="stylesheet" type="text/css">';
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

  echo "            <tr>
              <td>
                <table border=\"2\" cellpadding=\"6\" rules=\"rows\" width=\"100%\">
                  <tr bgcolor=\"#dddddd\">
                    <td width=\"100%\"><font size=\"+1\">$Titel</font></td>
                    <td align=\"right\"><font size=\"-1\">$datum</font></td>
                    <td align=\"right\"><font size=\"-1\">$zeit</font></td>";
  if ($ForumId == -1)
    echo "                <td align=\"right\"><font size=\"-1\"><nobr>Neue Beitr&auml;ge: $NumBeitraege</nobr></font></td>
                  </tr>
                  <tr>
                    <td bgcolor=\"#ffffff\" colspan=\"4\"><a href=\"posteingang.php\">$Inhalt</a></td>";
  else
    echo "                <td align=\"right\"><font size=\"-1\"><nobr>Themen: $NumBeitraege</nobr></font></td>
                  </tr>
                  <tr>
                    <td bgcolor=\"#ffffff\" colspan=\"4\"><a href=\"themen.php?fid=$ForumId&tid=-1\">$Inhalt</a></td>";
  echo "                  </tr>
                </table>
              </td>
            </tr>\n";
}

function zeichne_thema ($param)
{
  $Erster = $param['Erster'];
  $ForumId = $param['$ForumId'];
  $ThemaId = $param['ThemaId'];
  $Autor = $param['Autor'];
  $AutorURL = rawurlencode ($Autor);
  $AutorLetzter = $param['AutorLetzter'];
  $StempelLetzter = $param['StempelLetzter'];
  $Titel = $param['Titel'];
  $NumBeitraege = $param['NumBeitraege'];
  $NumGelesen = $param['NumGelesen'];
  $BaumZeigen = $param['BaumZeigen'];

  setlocale (LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
  $datum = strftime ("%d.%b.%Y", $StempelLetzter);
  $zeit = date ("H.i:s", $StempelLetzter);
  $aw = $NumBeitraege - 1;

  if ($BaumZeigen)
    $sprung = 'beitrags-baum';
  else
    $sprung = 'beitraege';
    
  echo "            <tr>
              <td>
                <table border=\"2\" cellpadding=\"6\" rules=\"rows\" width=\"100%\">
                  <tr  bgcolor=\"#dddddd\">
                    <td width=\"100%\"><font size=\"-1\">Autor: <b><a href=\"mitglieder-profil.php?alias=$AutorURL\">$Autor</a></b></font></td>
                    <td align=\"right\"><font size=\"-1\">$datum</font></td>
                    <td align=\"right\"><font size=\"-1\">$zeit</font></td>
                    <td align=\"right\"><font size=\"-1\">Aw:$aw</font></td>
                  </tr>
                  <tr>
                    <td bgcolor=\"#ffffff\" colspan=\"4\"><a href=\"$sprung.php?fid=$ForumId&tid=$ThemaId&bid=-1&sid=-1\">$Titel</a></td>
                  </tr>
                </table>
              </td>
            </tr>\n";
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
  $datum = strftime ("%d.%b.%Y", $StempelLetzter);
  $zeit = date ("H.i:s", $StempelLetzter);

  if ($Erster)
    echo "              <tr>
                  <td colspan=\"3\" align=\"center\"><h2>$Thema</h2></td>
                </tr>";
  echo "              <tr>
                <td colspan=\"3\">
                  <table border=\"2\" cellpadding=\"6\" rules=\"rows\" width=\"100%\">
                    <tr bgcolor=\"#dddddd\">
                      <td width=\"100%\"><font size=\"-1\">Autor: <b><a href=\"mitglieder-profil.php?alias=$AutorURL\">$Autor</a></font></td>
                      <td align=\"right\"><font size=\"-1\">$datum</font></td>
                      <td align=\"right\"><font size=\"-1\">$zeit</font></td>
                    </tr>
                    <tr>
                      <td bgcolor=\"#ffffff\" colspan=\"3\">$Inhalt</td>
                    </tr>
                  </table>
                </td>
              </tr>\n";

  // Die Antwort Zeile
  if ($Egl)
  {
    if ($Erster)
      echo "              <tr>
                <td><font size=\"-1\"><input type=\"radio\" name=\"eltern\" value=$BeitragId checked>Antworten auf diesen Beitrag</input></font></td>\n";
    else
      echo "              <tr>
                <td><font size=\"-1\"><input type=\"radio\" name=\"eltern\" value=$BeitragId>Antworten auf diesen Beitrag</input></font></td>\n";
    echo "                <td align=\"right\"><font size=\"-1\"><button type=\"submit\" name=\"zid\" value=$BeitragId>zitieren</button></td>
              </tr>
              <tr>
                <td><br></td>
              </tr>\n";
  }
  $start++;
}
?>
