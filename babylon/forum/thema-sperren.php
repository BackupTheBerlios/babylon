<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                       "http://www.w3.org/TR/html4/loose.dtd">
<?PHP;
/* Copyright 2004 Detlef Reichl <detlef!reichl()gmx!org>
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
  $K_ThemenJeSeite = 6;
  $K_Signatur ="";
  $K_SprungSpeichern = 0;
  $K_BaumZeigen = 'j';

  $fid = 0;
  $tid = 0;
  $sid = 0;
  $bid = 0;
  $zid = 0;
  $neu = FALSE;
  include ("get-post.php");
  if (id_von_get_post ($fid, $tid, $sid, $bid, $zid, $neu))
    die ("Illegaler Zugriffsversuch!");
  
  include ("../gemeinsam/benutzer-daten.php");

  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen,  $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil, $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_Admin & 1 << $fid)
    die ("Zugriff verweigert");

  $status = $_GET['sperren'] == 'n' ? 'n' : 'j';

  mysql_query ("UPDATE Beitraege
                SET Gesperrt = \"$status\"
                WHERE BeitragTyp = 2
                  AND ThemaId = \"$tid\"")
   or die ("Der Themenzugriffsstatus konnte nicht geaendert werden");
 if (!mysql_affected_rows ())
   die ('Der Zugriffsstatus konnte nicht aktualisiert werden.');

  $tid = intval ($_GET['tid_sprung']);
  include ("gz-themen.php");
?>
