<?PHP;
/* Copyright 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $Benutzer = '';
  $K_Egl = FALSE;
  $K_Lesen = 0;
  $K_Schreiben = 0;
  $K_Admin = 0;
  $K_AdminForen = 0;
  $K_ThemenJeSeite = 6;
  $K_BeitraegeJeSeite = 3;
  $K_Stil = 'std';
  $K_Signatur = '';
  $K_SprungSpeichern = 0;
  $K_BaumZeigen = 0;

  include ('../gemeinsam/db-verbinden.php');
  include ('../gemeinsam/benutzer-daten.php');

  $db = db_verbinden ();    
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil, $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ('Zugriff verweigert');

  if (isset ($_POST['vorlage']))
  {
    $zu_arg = 'vid=' . intval ($_POST['vorlage']);
  }
  $recht_lesen = 0;
  $recht_schreiben = 0;
  $recht_admin = 0;

  for ($i = 0; $i < 31; $i++)
  {
    if (isset ($_POST['rl' . $i]))
      $recht_lesen += 1 << $i;
    if (isset ($_POST['rs' . $i]))
      $recht_schreiben += 1 << $i;
    if (isset ($_POST['ra' . $i]))
      $recht_admin += 1 << $i;
  }

  $recht_admin_foren = isset ($_POST['raf']) ? '1' : '0';
  $recht_links = isset ($_POST['links']) ? 'j' : 'n';
  $recht_grafik = isset ($_POST['grafik']) ? 'j' : 'n';
  
  /* Speichern der Einstellungen als neue Vorlage */
  if (isset ($_POST['neue_vorlage']))
  {
    $name = addslashes ($_POST['vorlage_name']);

    $erg = mysql_query ("SELECT Name
                         FROM BenutzerVorlage
                         WHERE Name = '$name'")
       or die ('Die aktuelle Vorlage konnte nicht ermittelt werden');

    /* Es besteht bereits eine Vorlage mit gleichen Namen */
    if (mysql_num_rows ($erg))
    {
      $erg = mysql_query ("SELECT Name
                           FROM BenutzerVorlage
                           WHERE Name LIKE \"$name%\"")
        or die ('Die bestehenden Vorlagen konnten nicht ermittelt werden<br>' . mysql_error ());

       $namen = array ();
       while ($zeile = mysql_fetch_row ($erg))
         array_push ($namen, $zeile[0]);
       
       natsort ($namen);
       $letzter = end ($namen);
       $naechster_val = intval (preg_replace ('/^[a-zA-Z._ -]*([0-9]+)/', '\\1', $letzter)) + 1;
       $naechster_name = preg_replace ('/^([a-zA-Z._ -]*)[0-9]+/', '\\1', $letzter);
       $name = addslashes ($naechster_name.$naechster_val);
    }
    
    mysql_query ("INSERT INTO BenutzerVorlage
                             (Name, RechtLesen, RechtSchreiben,
                              RechtAdmin, RechtAdminForen, RechtLinks,
                              RechtGrafik)
                      VALUES ('$name', $recht_lesen, $recht_schreiben,
                               $recht_admin, $recht_admin_foren, '$recht_links',
                              '$recht_grafik')")
      or die ('Die neue Benutzervorlage konnte nicht angelegt werden<br>' . mysql_error ());

    $erg = mysql_query ("SELECT VorlageId
                         FROM BenutzerVorlage
                         WHERE Name = '$name'")
      or die ('Die neu angelegte Vorlage konnte nicht ermittelt werden<br>' . mysql_error ());
    $zeile = mysql_fetch_row ($erg);
    $zu_arg = 'vid=' . intval ($zeile[0]);
  }
  else
  {
    $name = isset ($_POST['speichern']) ? addslashes ($_POST['alte_vorlage']) : '';
    if ($name)
      mysql_query ("UPDATE BenutzerVorlage
                    SET RechtLesen = $recht_lesen,
                        RechtSchreiben = $recht_schreiben,
                        RechtAdmin = $recht_admin,
                        RechtAdminForen = $recht_admin_foren,
                        RechtLinks = '$recht_links',
                        RechtGrafik = '$recht_grafik'
                    WHERE Name = '$name'")
      or die ('Die Benutzervorlage konnte nicht aktualisiert werden<br>' . mysql_error ());
  }
$zu = 'benutzer-vorlage';
include ("gehe-zu.php");
?>
