<?PHP;

// StilName = XPerimental

/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function css_setzen ()
{
  echo '    <link href="stil/xp.css" rel="stylesheet" type="text/css">';
}

function zeichne_forum ($param)
{
  $Erster = $param['Erster'];
  $ForumId = $param['ForumId'];
  $NumBeitraege = $param['NumBeitraege'];
  $Titel = $param['Titel'];
  $Inhalt = $param['Inhalt'];
  setlocale (LC_TIME, 'de_DE');
  $datum = $param['StempelLetzter'] ? strftime ("%d.%b.%Y", $param['StempelLetzter']) : '';
  $zeit = $param['StempelLetzter'] ? date ("H.i:s", $param['StempelLetzter']) : '';
 
/*echo "<tr>
          <td class=\"ueber-forum\">$Titel</td>
          <td class=\"ueber-forum\"><font class=\"autor\">$datum $zeit</font></td>
        </tr><tr>
          <td class=\"col-hell\"><a href=\"themen.php?fid=$ForumId&tid=-1\">$Inhalt</a></td>
          <td class=\"col-hell\"><font class=\"datum\">Themen: $NumBeitraege</font></td>
        </tr>";*/

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

function zeichne_thema ($param)
{
  $Erster = $param['Erster'];
  $ForumId = $param['$ForumId'];
  $ThemaId = $param['ThemaId'];
  $Autor = $param['Autor'];
  $AutorLetzter = $param['AutorLetzter'];
  $StempelLetzter = $param['StempelLetzter'];
  $Titel = $param['Titel'];
  $NumBeitraege = $param['NumBeitraege'];
  $NumGelesen = $param['NumGelesen'];
  $BaumZeigen = $param['BaumZeigen'];

  setlocale (LC_TIME, 'de_DE');
  $datum = strftime ("%d.%b.%Y", $StempelLetzter);
  $zeit = date ("H.i:s", $StempelLetzter);
  $aw = $NumBeitraege - 1;

  if ($BaumZeigen)
    $sprung = 'beitrags-baum';
  else
    $sprung = 'beitraege';
    
  if ($Erster)
  {
    echo '          <table class="thema">
            <tr>
              <th class="ueber" width="100%" align="left">Thema / Erstellt von</th>
              <th class="ueber">Letzter Beitrag</th>
              <th class="ueber">Antworten</th>
              <th class="ueber">Lesungen</th>
            </tr>';
  }

  echo "            <tr>
              <td class=\"col-hell\">
                <font class=\"thema\"><a href=\"$sprung.php?fid=$ForumId&tid=$ThemaId&bid=-1&sid=-1\">$Titel</a></font><br><font class=\"autor\"><a href=\"mitglieder-profil.php?alias=$Autor\">$Autor</a></font>
              </td>
              <td class=\"col-dunkel\" align=\"center\">
                <font class=\"datum\">$datum $zeit<br><a href=\"mitglieder-profil.php?alias=$AutorLetzter\">$AutorLetzter</a></font>
              </td>
              <td class=\"col-hell\" align=\"center\">$aw</td>
              <td class=\"col-dunkel\"align=\"center\">$NumGelesen</td>
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
  $StempelLetzter = $param['StempelLetzter'];
  $Thema = $param['Thema'];
  $Inhalt = $param['Inhalt'];
  $Egl = $param['Egl'];
  $Atavar = $param['Atavar'];
  
  setlocale (LC_TIME, 'de_DE');
  $datum = strftime ("%d.%b.%Y", $StempelLetzter);
  $zeit = date ("H.i:s", $StempelLetzter);

  if ($Erster)
    echo "      <table class=\"beitrag\" width=\"100%\">
            <tr>
              <th class=\"ueber\" align=\"center\">$Thema</th>
            </tr>";

  echo "       <table class=\"beitrag\" width=\"100%\">
            <tr>
              <th class=\"ueber\" align=\"left\" colspan=\"2\"><a href=\"mitglieder-profil.php?alias=$Autor\">$Autor</a></th>
              <th class=\"ueber\" align=\"right\">$datum $zeit</th>
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
  echo '            </tr>
          </table>';

  // Die Antwort Zeile
  if ($Egl)
  {
      echo "            <table width=\"100%\">
      <tr>
                <td><font size=\"-1\"><input type=\"radio\" name=\"eltern\" value=$BeitragId";
                
      if ($Erster)
        echo ' checked';
      echo ">Antworten auf diesen Beitrag</input></font></td>
                <td align=\"right\"><font size=\"-1\"><button type=\"submit\" name=\"zid\" value=$BeitragId>zitieren</button></td>
              </tr>
            </table>\n";
  }
}
?>
