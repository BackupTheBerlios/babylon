<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $K_Egl = FALSE;
  $K_Lesen = 0;
  $K_Schreiben = 0;
  $K_Admin = 0;
  $K_AdminForen = 0;
  $K_Stil = 'std';
  $K_Signatur = '';
  $K_SprungSpeichern = 0;
  $K_BaumZeigen = 'j';

  include_once ('../konf/konf.php');
  include ('../../gemeinsam/benutzer-daten.php');

  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ('Zugriff verweigert');

  if (!isset ($_GET['modul']))
    die ('Es wurde kein Wartungsmodul angegeben');

  $modul = $_GET['modul'];
  if (!is_readable ("module/$modul.php"))
    die ("Zugriff auf Modul $modul wurde verweigert oder es exestiert nicht");

  echo '<html>
  <body bgcolor="#eeeeee">';

  include ("module/$_GET[modul].php");
  call_user_func ("$_GET[modul]_wartung");

  echo '<a href="wartung.php">Zur&uuml;ck zur Wartungshauptseite</a>
    </body>
  </html>';
;?>
