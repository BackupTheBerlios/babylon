<?PHP;
// StilName = Nuts'n'Bolts

/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function css_setzen ()
{
  echo '    <link href="stil/nandb.css" rel="stylesheet" type="text/css">';
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

  if (!$Erster)
    echo '<tr>
      <td class="schraube"></td>
      <td></td>
      <td></td>
      <td class="schraube"></td>
    </tr>';
  echo "    <tr>
      <td class=\"ecke\"></td>
      <td class=\"leiste-og\">$Titel</td>
      <td class=\"leiste-ok\" align=\"right\">$datum $zeit</td>
      <td class=\"ecke\"></td>
    </tr>
    <tr>
      <td class=\"ecke2\"></td>";

  if ($ForumId == -1)
    echo "    <td class=\"leiste-uk\"><a href=\"posteingang.php\">$Inhalt</a></td>
      <td class=\"leiste-uk\" align=\"right\">Neue Beitr&auml;ge: $NumBeitraege</td>";
  else
    echo "    <td class=\"leiste-uk\"><a href=\"themen.php?fid=$ForumId&tid=-1\">$Inhalt</a></td>
      <td class=\"leiste-uk\" align=\"right\">Themen: $NumBeitraege</td>";

  echo '    <td class="ecke2"></td>
    </tr>';
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

  if (strlen ($AutorLetzter))
    $autor = $Autor . ' >>>>> ' . $AutorLetzter;
  else
    $autor = $Autor;


  if (!$Erster)
    echo '<tr>
      <td class="schraube"></td>
      <td></td>
      <td></td>
      <td class="schraube"></td>
    </tr>';
  echo "    <tr>
      <td class=\"ecke2\"></td>
      <td class=\"leiste-uk\">$autor</td>
      <td class=\"leiste-uk\" align=\"right\">$datum $zeit</td>
      <td class=\"ecke2\"></td>
    </tr>
    <tr>
      <td class=\"ecke\"></td>
      <td class=\"leiste-og\"><a href=\"$sprung.php?fid=$ForumId&tid=$ThemaId&bid=-1&sid=-1\">$Titel</a></td>
      <td class=\"leiste-ok\" align=\"right\">Lesungen: $NumGelesen &nbsp; Antworten: $aw</td>
      <td class=\"ecke\"></td>
    </tr>";
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
      echo "    <tr>
      <td class=\"ecke\"></td>
      <td class=\"leiste-og\" colspan=\"2\" align=\"center\">$Thema</td>
      <td class=\"ecke\"></td>
    </tr>";
    
  echo "    <tr>
      <td class=\"ecke2\"></td>
      <td class=\"leiste-uk\">$Autor</td>
      <td class=\"leiste-uk\" align=\"right\">$datum $zeit</td>
      <td class=\"ecke2\"></td>
    </tr>
    <tr>
     <td class=\"schraube\"></td>
     <td colspan=\"2\"><div class=\"inhalt\">
        <table width=\"100%\" border=\"0\">
          <tr>";
  if ($Atavar > -1)
  {
echo "            <td valign=\"top\">
              <div class=\"atavar\">
                <img src=\"atavar-ausgeben.php?atavar=$Atavar\">
              </div></td>
            <td class=\"inhalt\" valign=\"top\" width=\"100%\">$Inhalt</td>";
  }
  else
  echo "            <td class=\"inhalt\" valign=\"top\" width=\"100%\" colspan=\"2\">$Inhalt</td>"; 
  echo '          </tr>
        </table>
      <td class="schraube"></td>
    </tr>'; 

  // Die Antwort Zeile
  if ($Egl)
  {
    echo "              <tr>
                <td class=\"ecke2\">
                <td class=\"leiste-uk\"><input type=\"radio\" name=\"eltern\" value=$BeitragId";
                
    if ($Erster)
      echo ' checked';
                
    echo ">Antworten auf diesen Beitrag</input></td>
                <td class=\"leiste-uk\" align=\"right\"><button type=\"submit\" name=\"zid\" value=$BeitragId>zitieren</button></td>
                <td class=\"ecke2\">
              </tr>\n";
  }
  $start++;
}
?>
