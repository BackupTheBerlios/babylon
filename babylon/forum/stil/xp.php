<?PHP;

// StilName = XPerimental

/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function css_setzen ()
{
  echo "\n    <link href=\"stil/xp.css\" rel=\"stylesheet\" type=\"text/css\">\n";
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
    
    if ($erster)
    {
      echo "          <table class=\"thema\">
            <tr>
              <th class=\"ueber\" width=\"100%\" align=\"left\">Thema / Erstellt von</td>
              <th class=\"ueber\">Letzter Beitrag</td>
              <th class=\"ueber\">Antworten</td>
              <th class=\"ueber\">Lesungen</td>
            </tr>\n";
    }

echo "            <tr>
              <td class=\"col-hell\"><font class=\"thema\"><a href=\"$sprung.php?fid=$ForumId&tid=$ThemaId&bid=-1&sid=-1\">$Titel</a></font><br><font class=\"autor\"><a href=\"mitglieder-profil.php?alias=$Autor\">$Autor</a></font></td>
              <td class=\"col-dunkel\" align=\"center\"><font class=\"datum\">$datum $zeit<br><a href=\"mitglieder-profil.php?alias=$AutorLetzter\">$AutorLetzter</a></font></td>
              <td class=\"col-hell\" align=\"center\">$aw</td>
              <td class=\"col-dunkel\"align=\"center\">$NumGelesen</td>
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

  echo "       <table class=\"beitrag\" width=\"100%\">
            <tr>
              <th class=\"ueber\" align=\"left\" colspan=\"2\"><a href=\"mitglieder-profil.php?alias=$Autor\">$Autor</a></td>
              <th class=\"ueber\" align=\"right\">$datum $zeit</td>
            </tr>
            <tr>";
  if ($Atavar > -1)
  {
    $datei = "atavar/$Atavar.jpg";
    echo"              <td class=\"col-dunkel\" valign=\"top\"><div class=\"atavar\"><img src=\"$datei\"></div></td>
              <td class=\"col-hell\" valign=\"top\" colspan=\"2\" width=\"100%\">$Inhalt</td>";
  }
  else
   {
    echo"             <td class=\"col-hell\" valign=\"top\" colspan=\"3\" width=\"100%\">$Inhalt</td>";
  } 
  echo "            </tr>
          </table>\n";

  // Die Antwort Zeile
  if ($K_Egl)
  {
      echo "            <table width=\"100%\">
      <tr>
                <td><font size=\"-1\"><input type=\"radio\" name=\"eltern\" value=$BeitragId";
                
      if ($erster)
        echo " checked";
      echo ">Antworten auf diesen Beitrag</input></font></td>
                <td align=\"right\"><font size=\"-1\"><button type=\"submit\" name=\"zid\" value=$BeitragId>zitieren</button></td>
              </tr>
            </table>\n";
  }
}
?>
