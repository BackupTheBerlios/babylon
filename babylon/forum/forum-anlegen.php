<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

// Standart Konfiguration. Man darf absolut nix ;-)
  $K_Admin = 0;
  $K_AdminForen = 0;

  include ("../gemeinsam/db-verbinden.php");
  include ("../gemeinsam/benutzer-daten.php");
  include ("konf/konf.php");

  $db = db_verbinden ();
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen,  $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil, $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ("Du hast keine Zugriffsrechte auf diese Seite");

  $erg = mysql_query ("SELECT ForumId, Gesperrt, Titel, Inhalt
                       FROM Beitraege
                       WHERE BeitragTyp = 1
                       ORDER BY ForumId")
    or die ("Die bestehenden Foren konnten nicht ermittelt werden");

  echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
<html>
<head>
  <link href=\"/forum/stil/std.css\" rel=\"stylesheet\" type=\"text/css\">
  <title>$B_seitentitel_start / Forum Anlegen</title>
</head>
<body>
  <h2>Forum anlegen</h2>
  <table border=\"0\">
    <form action=\"forum-anlegen-test.php\" method=\"post\"
      <tr>
        <td>Titel</td>
        <td><input name=\"titel\" type=\"text\" size=\"30\" maxlength=\"255\" value=\"$_POST[titel]\"><p></td>
      </tr>
      <tr>
        <td>Beschreibung</td>
        <td><input name=\"inhalt\" type=\"text\" size=\"30\" maxlength=\"255\" value=\"$_POST[inhalt]\"></td>
      </tr>
      <tr>
        <td>
          <h3>Position</h3>
        </td>
      </tr>\n";
  $x = 0;
  while ($zeile = mysql_fetch_row ($erg))
  {
    echo "      <tr>
        <td colspan=\"2\">\n";
    if ($zeile[1] == 'j')
      echo "<nobr><input type=\"radio\" name=\"forum_id\" value=\"$zeile[0]\">
              <font color=\"#aaaaaa\">Nr. $x Frei</font>
          </input></nobr>\n";
    else
      echo "$zeile[2]<br><font size=\"-1\">$zeile[3]</font>\n";
    echo "        <hr align=\"left\" width=\"100%\">
        </td>
      </tr>";
    $x++;
  }
  echo"      <tr>
        <td colspan=\"2\">
          <button type=\"submit\">
            <img src=\"/grafik/Ausfuehren.png\" width=\"24\" height=\"24\" alt=\"\">Anlegen
          </button>
        </td>
      </tr>
    </form>
  </table>
</body>
</html>";
;?>

