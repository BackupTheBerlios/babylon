<?PHP;
/* Copyright 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  include_once ('konf/konf.php');
  include_once ('fehler.php');
  include_once ('wartung/wartung-info.php');
  include ('../gemeinsam/benutzer-daten.php');
  include ('../gemeinsam/msie.php');
  include ('konf/meta.php');
  include ('leiste-oben.php');
  include ('get-post.php');

  $K_ThemenJeSeite = $B_themen_je_seite;
  $K_BeitraegeJeSeite = $B_beitraege_je_seite;
  $K_Stil = $B_standart_stil;

// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $Benutzer = '';
  $K_Egl = FALSE;
  $K_Lesen = 0;
  $K_Schreiben = 0;
  $K_Admin = 0;
  $K_AdminForen = 0;
  $K_Signatur = '';
  $K_SprungSpeichern = 0;
  $K_BaumZeigen = 'j';

  $fid = 0;
  $tid = 0;
  $sid = 0;
  $bid = 0;
  $zid = 0;
  $neu = 0;

  if (id_von_get_post ($fid, $tid, $sid, $bid, $zid, $neu))
    die ('Illegaler Zugriffsversuch!');

  $msiepng = msie_png ();

  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil, $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"
   \"http://www.w3.org/TR/html4/loose.dtd\">

<html>
  <head>\n";
  metadata ($_SERVER['SCRIPT_FILENAME']);

  if (isset ($skript) && $skript == 'j')
    echo "  <script src=\"js/formathilfe.js\" type=\"text/javascript\"></script>\n";

  $stil_datei = "stil/$K_Stil.php";
  include ($stil_datei);
  css_setzen ();

  echo "    <title>$titel</title>
  </head>\n";

  if (isset ($skript) && $skript == 'j'
      && 1 << $fid & $K_Schreiben
      && $K_Egl
      && $neu
      && $_GET['vorschau'] != 'j')
    echo "<body onLoad=\"document.eform.titel.focus()\">\n";
  else
    echo "<body>\n";
  
  wartung_ankuendigung ();
  leiste_oben ($K_Egl, $B_startseite_link);
;?>
