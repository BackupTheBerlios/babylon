<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

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

  $pfad = $_SERVER['DOCUMENT_ROOT'] . '/forum/konf/konf.php';
  include ("$pfad");
  if ($B_version != 0 or $B_subversion < 2)
    die ('Das Modul zur Konfiguration der Systemvariablen steht erst ab Version 0.2
          des Forums zur Verf&uuml;gung');
  
  $pfad = $_SERVER['DOCUMENT_ROOT'] . '/gemeinsam/benutzer-daten.php';
  include_once ("$pfad");

  $db = db_verbinden ();    
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ('Zugriff verweigert');

  if (!$B_wartung_start)
    die ('Um dieses Skript laufen zu lassen muss das Forum gesperrt sein');

  $pfad = $_SERVER['DOCUMENT_ROOT'] . '/forum/wartung/konf-schreiben.php';
  include ("$pfad");


  $vars = array ('betreiber', 'seitentitel_start', 'startseite_link', 'mail_absender',
                 'cookie_id', 'cookie_sw');

  reset ($vars);
  while ($var = current ($vars))
  {
    $val = addslashes ($_POST["$var"]);
    konf_schreiben ("B_$var", $val);
    next ($vars);
  }

  $vars = array ('atavar_max_kb', 'atavar_max_breite', 'atavar_max_hoehe', 'themen_je_seite',
                 'beitraege_je_seite');

  reset ($vars);
  while ($var = current ($vars))
  {
    $val = intval ($_POST["$var"]);
    if (!$val)
      die ("Die Varialbe $var hat einen 0-Wert. Dies ist nicht zul&auml;ssig");
    konf_schreiben ("B_$var", $val);
    next ($vars);
  }
 
  konf_schreiben ('B_profil_links', isset ($_POST["profil_links"]) ? TRUE : FALSE);

 
  $pfad = $_SERVER['DOCUMENT_ROOT'] . '/forum/wartung/gehe-zu.php';
  $zu = '/forum/wartung/wartung';
  include ("$pfad");
?>
