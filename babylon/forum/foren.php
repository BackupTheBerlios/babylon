<!--DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                       "http://www.w3.org/TR/html4/loose.dtd"-->
<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  include_once ("konf/konf.php");
  include ("wartung/wartung-info.php");

// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $Benutzer = "";
  $K_Egl = FALSE;
  $K_Lesen = 0;
  $K_Schreiben = 0;
  $K_Admin = 0;
  $K_AdminForen = 0;
  $K_Signatur ="";
  $K_SprungSpeichern = 0;
  $K_BaumZeigen = 'j';

  include ("../gemeinsam/benutzer-daten.php");
  include_once ("../gemeinsam/msie.php");
  $msiepng = msie_png ();
  include ("leiste-oben.php");

  $K_Stil = $B_standart_stil;

  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  echo "<html>
  <head>\n";
  include ("konf/meta.php");
  metadata ($_SERVER["SCRIPT_FILENAME"]);

  $stil_datei = "stil/" . $K_Stil . ".php";
  include ($stil_datei);
  css_setzen ();

  echo "    <title>Forum / Foren</title>
  </head>
  <body>";
  
  wartung_ankuendigung ();
  
  echo "    <table width=\"100%\">\n";

  $gehe_zu = "themen";
  leiste_oben ($K_Egl);
  echo "        <tr>
          <td>
            <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
  
  $beitraege = mysql_query ("SELECT ForumId, NumBeitraege, StempelLetzter, Titel, Inhalt
                             FROM Beitraege
                             WHERE BeitragTyp = 1 AND Gesperrt = 'n'")
    or die ("F0042: Forenzahl konnte nicht ermittelt werden");

  $erster = TRUE;
  $f = 0;
  while ($zeile = mysql_fetch_row ($beitraege))
  {
    if ((1 << $f & $K_Lesen) or (1 << $f & $B_leserecht))
    {
      zeichne_forum ($erster, $zeile[0], $zeile[1], $zeile[2], $zeile[3], $zeile[4]);
      $erster = FALSE;
    }
    $f++;
  }

  mysql_close ($db);
  echo "          </table>
        </td>
      </tr>
      <tr>
        <td><img src=\"/grafik/dummy.png\" width=\"1\" height=\"30\" alt=\"\"></td>
      </tr>\n";
 
  if ($K_AdminForen)
  {
    echo "      <tr>
        <td>
          <form action=\"forum-anlegen.php\" method=\"post\">
            <button type=\"submit\"><img src=\"/grafik/Typewriter$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Neues Forum</button>
          </form>
        </td>
      </tr>\n";
  }
  include ("leiste-unten.php");
  leiste_unten ();
  echo "    </table>
  </body>
</html>";
?>
