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
  setlocale (LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
  $datum = $param['StempelLetzter'] ? strftime ("%d.%b.%Y", $param['StempelLetzter']) : '';
  $zeit = $param['StempelLetzter'] ? date ("H.i:s", $param['StempelLetzter']) : '';

  echo "            <tr>
              <td>
                <table border=\"2\" rules=\"rows\" width=\"100%\" cellpadding=\"6\">
                  <tr bgcolor=\"#dddddd\">
                    <td width=\"100%\"><font size=\"+1\"><b>$Titel</b></font></td>
                    <td align=\"right\"><font size=\"-1\">$datum</font></td>
                    <td align=\"right\"><font size=\"-1\">$zeit</font></td>";
  if ($ForumId == -1)
    echo "                  <td align=\"right\"><font size=\"-1\">Neue&nbsp;Beit&auml;ge:&nbsp;$NumBeitraege</font></td>
                  </tr>
                  <tr>
                    <td bgcolor=\"#ffffff\" colspan=\"4\"><a href=\"posteingang.php\">$Inhalt</a></td>";
  else
    echo "                  <td align=\"right\"><font size=\"-1\">Themen:&nbsp;$NumBeitraege</font></td>
                  </tr>
                  <tr>
                    <td bgcolor=\"#ffffff\" colspan=\"4\"><a href=\"themen.php?fid=$ForumId&amp;tid=-1\">$Inhalt</a></td>"; 
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
  $AutorLetzterURL = rawurlencode ($AutorLetzter);
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
    
  if ($Erster)
  {
    echo '            <tr>
              <th class="ueber" width="100%" align="left">Thema / Erstellt von</th>
              <th class="ueber">Letzter Beitrag</th>
              <th class="ueber">Antworten</th>
              <th class="ueber">Lesungen</th>
            </tr>';
  }

  echo "            <tr>
              <td class=\"col-hell\">
                <font class=\"thema\"><a href=\"$sprung.php?fid=$ForumId&amp;tid=$ThemaId&amp;bid=-1&amp;sid=-1\">$Titel</a></font><br><font class=\"autor\"><a href=\"mitglieder-profil.php?alias=$AutorURL\">$Autor</a></font>
              </td>
              <td class=\"col-dunkel\" align=\"center\">
                <font class=\"datum\">$datum&nbsp;$zeit<br><a href=\"mitglieder-profil.php?alias=$AutorLetzterURL\">$AutorLetzter</a></font>
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
    echo "      <tr>
        <th class=\"ueber\" align=\"center\" width=\"100%\" colspan=\"2\">$Thema</th>
      </tr>\n";

  echo "      <tr>
        <td colspan=\"2\">
          <table class=\"beitrag\">
            <tr>
              <th class=\"ueber\" align=\"left\" colspan=\"2\"><a href=\"mitglieder-profil.php?alias=$AutorURL\">$Autor</a></th>
              <th class=\"ueber\" align=\"right\">$datum $zeit</th>
            </tr>
            <tr>\n";
  if ($Atavar > -1)
  {
    echo"              <td class=\"col-dunkel\" valign=\"top\">
                <div class=\"atavar\">
                  <img src=\"atavar-ausgeben.php?atavar=$Atavar\">
                </div>
              </td>
              <td class=\"col-hell\" valign=\"top\" colspan=\"2\" width=\"100%\">$Inhalt</td>\n";
  }
  else
   {
    echo"              <td class=\"col-hell\" valign=\"top\" colspan=\"3\" width=\"100%\">$Inhalt</td>\n";
  } 
  echo "            </tr>
          </table>
        </td>
      </tr>\n";

  // Die Antwort Zeile
  if ($Egl)
  {
      echo "      <tr>
        <td><font size=\"-1\"><input type=\"radio\" name=\"eltern\" value=$BeitragId";
                
      if ($Erster)
        echo ' checked';
      echo ">Antworten auf diesen Beitrag</font></td>
        <td align=\"right\"><font size=\"-1\"><button type=\"submit\" name=\"zid\" value=$BeitragId>zitieren</button></font></td>
      </tr>\n";
  }
}
?>
