<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                       "http://www.w3.org/TR/html4/loose.dtd">
<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */


// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $Benutzer = "";
  $K_Egl = FALSE;
  $K_Lesen = 0;
  $K_Schreiben = 0;
  $K_Admin = 0;
  $K_AdminForen = 0;
  $K_Stil = "std";
  $K_Signatur ="";
  $K_SprungSpeichern = 0;
  $K_BaumZeigen = 'j';

  include ("../gemeinsam/db-verbinden.php");
  include ("../gemeinsam/benutzer-daten.php");
  include_once ("../gemeinsam/msie.php");
  include_once ("konf/konf.php");
  $msiepng = msie_png ();
  include ("leiste-oben.php");

  $db = db_verbinden ();    
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

  $alias = addslashes ($_GET[alias]);

  $erg = mysql_query ("SELECT Benutzer, VName, NName, Anmeldung, Beitraege,
                              Themen, LetzterBeitrag, Atavar, ProfilNameZeigen,
                              ProfilEMail, ProfilHomepage, ProfilOrt
                       FROM Benutzer
                       WHERE Benutzer = \"$alias\"")
    or die ("Profildaten konnten nicht ermittelt werden");
  if (!mysql_num_rows ($erg))
    die ("Ungueltige Benutzerkennung");

  $profil = mysql_fetch_row ($erg);

  echo "  <title>Forum / Profil von $profil[0]</title>
</head>
<body>
  <table width=\"100%\">\n";

  $gehe_zu = "foren";
  leiste_oben ($K_Egl);
  
  if ($profil[8] == 'j')
    $name = "$profil[1] $profil[2]<br>";
  else
    $name = "";
    
  if ($profil[6])
  {
    setlocale (LC_TIME, "de_DE");
    $datum = strftime ("%d.%b.%Y", $profil[6]);
    $zeit = date ("H.i:s", $profil[6]);
    $letzter_beitrag = "Letzter Beitrag am $datum um $zeit<br>";
  }

  if ($profil[11])
    $ort = "Wohnort: $profil[11]";
  else
    $ort = "";
  
  echo "    </table>
        <table border=\"2\">
          <tr>
            <td>
              Atavar
            </td>
            <td>
              $profil[0]<br>
              $name
              $ort
            </td>
            <td>
              $profil[4] Beitr&auml;ge geschrieben<br>
              $profil[5] Themen er&ouml;ffnet<br>
              $letzter_beitrag
            </td>
          </tr>
          <tr>
            <td colspan=\"3\">
              Email: $profil[9]<br>
              Homepage: $profil[10]
            </td>
          </tr>
        </table>";

  include ("leiste-unten.php");
  leiste_unten ();

  echo "    </table>
  </body>
</html>";
?>
