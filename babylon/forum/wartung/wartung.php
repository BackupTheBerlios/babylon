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

  include ('../konf/konf.php');
  include ('../../gemeinsam/benutzer-daten.php');

  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ('Zugriff verweigert');

  echo '<html>
  <body bgcolor="#eeeeee">
    <h1>Wartungssystem</h1>';

  if (!$B_wartung_start)
  {
    echo 'Das Forum ist momentan noch nicht gesperrt. Dies ist zur Benutzung des Wartungssystems
          notwendig.
  
    <form action="wartung-aktivieren.php" method="post">
      <table  cellspacing="40">
        <tr>
          <td align="center">
            Begin der Wartung in:<br>
            <select name="start" size="1">
              <option>sofort</option>
              <option>5 Minuten</option>
              <option>10 Minuten</option>
              <option>15 Minuten</option>
              <option>30 Minuten</option>
              <option>45 Minuten</option>
              <option>60 Minuten</option>
            </select>
          </td>
          <td align="center">
            Dauer der Wartung:<br>
            <select name="dauer" size="1">
              <option>5 Minuten</option>
              <option>15 Minuten</option>
              <option>30 Minuten</option>
              <option>60 Minuten</option>
              <option>2 Stunden</option>
              <option>4 Stunden</option>
            </select>
          </td>       
        </tr>
        <tr>
          <td>
            <button>Wartungsmodus<br>aktivieren</button>
          </td>
        </tr>
      </table>
    </form>';
  }
  else
  {
    if ($B_wartung_start > time () + 5)  // nur zur Sicherheit ein Zeitfenster von 5 sek.
    {
      $start = $B_wartung_start - time ();
      if ($start <= 120)
        echo "Die Wartung begint in $start Sekunden<p>";
      else
      {
        $start = intval ($start / 60);
        echo "Die Wartung begint in $start Minuten<p>";
      }
    }
    else
    {
      echo '<h3>Das Forum ist momentan gesperrt</h3>';
      $dauer = intval (($B_wartung_ende - time ()) / 60);
      if ($dauer <= 0)
        echo '<h5>Das angek&uuml;ndigte Ende der Wartung ist bereits erreicht</h5>';
      else
        echo "<h5>F&uuml;r die Wartung sind noch etwa $dauer Minuten veranschlagt</h5>";
      echo '<h3>folgende Wartungsmodule stehen zur Verf&uuml;gung:</h3>'; 

      $handle = opendir ('module');
      $verz = array ();
      while ($datei = readdir ($handle))
        if (preg_match ('/^\w+\.php/', $datei))
          array_push ($verz, $datei);
      closedir ($handle);

      reset ($verz);
      while ($datei = current ($verz))
      {
        $pfad = "module/$datei";
        include "$pfad";
        $modul = preg_replace ('/module\/(\w+).php/', '\\1', $pfad);
        $titel = $modul . '_titel';
        $beschreibung = $modul . '_beschreibung';
      
        echo "<h3>
                <a href=\"modul-aufrufen.php?modul=$modul\">";
        call_user_func ($titel);
        echo '</a>
            </h3>';
      
        call_user_func ($beschreibung);
        echo '<p>';
      
        next ($verz);
      }
    }
    echo '<form action="wartung-deaktivieren.php" method="post">
      <button>Wartungsmodus<br>beenden</button>
    </form>';
  }
  echo '</body>
  </html>';
?>
