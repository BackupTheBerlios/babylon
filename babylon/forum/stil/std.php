<?PHP;
// StilName = Standart

/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function css_setzen ()
{
  echo "\n    <link href=\"stil/std.css\" rel=\"stylesheet\" type=\"text/css\">\n";
}

function zeichne_forum ($erster, $ForumId, $NumBeitraege, $StempelLetzter, $Titel, $Inhalt)
{
    setlocale (LC_TIME, "de_DE");
    $datum = strftime ("%d.%b.%Y", $StempelLetzter);
    $zeit = date ("H.i:s", $StempelLetzter);
  echo "            <tr>
              <td>
                <table border=\"2\" cellpadding=\"6\" rules=\"rows\" width=\"100%\">
                  <tr bgcolor=\"#dddddd\">
                    <td width=\"100%\"><h3>$Titel</h3></td>
                    <td align=\"right\"><font size=\"-1\">$datum</font></td>
                    <td align=\"right\"><font size=\"-1\">$zeit</font></td>
                    <td align=\"right\"><font size=\"-1\"><nobr>Themen: $NumBeitraege</nobr></font></td>
                  </tr>
                  <tr>
                    <td bgcolor=\"#ffffff\" colspan=\"4\"><a href=\"themen.php?fid=$ForumId&tid=-1\">$Inhalt</a></td>
                  </tr>
                </table>
              </td>
            </tr>\n";
}

function zeichne_thema ($erster, $ForumId, $ThemaId, $Autor, $AutorLetzter, $StempelLetzter, $Titel, $NumBeitraege, $NumGelesen, $BaumZeigen)
{
    setlocale (LC_TIME, "de_DE");
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
                    <td width=\"100%\"><font size=\"-1\">Autor: <b>$Autor</b></font></td>
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

function zeichne_beitrag ($erster, $ForumId, $BeitragId, $Autor, $StempelLetzter, $Thema, $Inhalt, $K_Egl, $Atavar)
{
  setlocale (LC_TIME, "de_DE");
  $datum = strftime ("%d.%b.%Y", $StempelLetzter);
  $zeit = date ("H.i:s", $StempelLetzter);

  if ($erster)
    echo "              <tr>
                  <td colspan=\"3\" align=\"center\"><h2>$Thema</h2></td>
                </tr>";
  echo "              <tr>
                <td colspan=\"3\">
                  <table border=\"2\" cellpadding=\"6\" rules=\"rows\" width=\"100%\">
                    <tr bgcolor=\"#dddddd\">
                      <td width=\"100%\"><font size=\"-1\">Autor: <b>$Autor</font></td>
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
  if ($K_Egl)
  {
    if ($erster)
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
