<?PHP;

// StilName = Text

/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function css_setzen ()
{
  echo '<link href="stil/text.css" rel="stylesheet" type="text/css">';
}

// laengen:
// gesamt 80
// datum / zeit 8
// anzahl 5

function zeichne_forum ($param)
{
  $Erster = $param['Erster'];
  $ForumId = $param['ForumId'];
  $NumBeitraege = $param['NumBeitraege'];
  $Titel = $param['Titel'];
  $Inhalt = $param['Inhalt'];
  setlocale (LC_TIME, 'de_DE');
  $datum = strftime ("%d.%m.%y", $param['StempelLetzter']);
  $zeit = date ("H.i:s", $param['StempelLetzter']);
  $titel = str_pad (substr (strip_tags ($Titel), 0, 58), 59);
  $inhalt = str_pad (substr (strip_tags ($Inhalt), 0, 58), 59);

    echo "<tr><td><pre>\n";
    if ($erster)
    {
      echo "Forum                                                        letzter    Anzahl \n";
      echo "                                                             Beitrag    Themen \n";
      echo "-------------------------------------------------------------------------------\n";
    }
      echo "$titel  $datum   $NumBeitraege\n";
      echo "<a href=\"themen.php?fid=$ForumId&tid=-1\">$inhalt</a>  $zeit\n\n";
    echo "</pre></td></tr>\n";
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
  $datum = strftime ("%d.%m.%y", $StempelLetzter);
  $zeit = date ("H.i:s", $StempelLetzter);
  $autor = str_pad (substr (strip_tags ($Autor), 0, 58), 59);
  $titel = str_pad (substr (strip_tags ($Titel), 0, 58), 59);
  $aw = $NumBeitraege - 1;
  if ($BaumZeigen)
    $sprung = 'beitrags-baum';
  else
    $sprung = 'beitraege';

  echo "<tr><td><pre>\n";
  if ($Erster)
  {
    echo "Autor                                                        letzter    Anzahl \n";
    echo "   Thema                                                     Beitrag  Antworten\n";
    echo "-------------------------------------------------------------------------------\n";
  }
  echo "$autor  $datum   $aw\n";
  echo "<a href=\"$sprung.php?fid=$ForumId&tid=$ThemaId&bid=-1&sid=-1\">$titel</a>  $zeit\n";
  echo "</pre></td></tr>\n";
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
  $datum = strftime ("%d.%m.%y", $StempelLetzter);
  $zeit = date ("H.i:s", $StempelLetzter);
  $autor = str_pad (substr (strip_tags ($Autor), 0, 68), 69);
  $thema = str_pad (substr (strip_tags ($Thema), 0, 80), 80, " ", STR_PAD_BOTH);
  $inhalt = wordwrap (strip_tags ($Inhalt), 80);
  
    echo "<tr><td><pre>\n";
    if ($Erster)
    {
      echo "$thema\n";
      echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n\n";
    }
    echo "Autor                                                                    Datum \n";
    echo "-------------------------------------------------------------------------------\n";
    echo "$autor  $datum\n";
    echo "...............................................................................\n";
    echo "$inhalt\n";
    echo "같같같같같같같같같같같같같같같같같같같같같같같같같같같같같같같같같같같같같같같�";
    echo "</pre></td></tr>\n";

  // Die Antwort Zeile
  if ($Egl)
  {
    if ($Erster)
      echo "<tr><td><font size=\"-1\"><input type=\"radio\" name=\"eltern\" value=$BeitragId checked>Antworten auf diesen Beitrag</input></td>";
    else
      echo "<tr><td><font size=\"-1\"><input type=\"radio\" name=\"eltern\" value=$BeitragId>Antworten auf diesen Beitrag</input></td>";
    echo "</font></tr><tr><td><br></td></tr>";
  }
}
?>
