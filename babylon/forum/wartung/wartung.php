<?PHP;
// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $K_Egl = FALSE;
  $K_Lesen = 0;
  $K_Schreiben = 0;
  $K_Admin = 0;
  $K_AdminForen = 0;
  $K_Stil = "std";
  $K_Signatur ="";
  $K_SprungSpeichern = 0;
  $K_BaumZeigen = 'j';

  include ("../../gemeinsam/db-verbinden.php");
  include ("../../gemeinsam/benutzer-daten.php");
  include_once ("../konf/konf.php");

  $db = db_verbinden ();    
  benutzer_daten_forum ($BenutzerId, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ("Zugriff verweigert");

  echo "<h1>Wartungssystem</h1>";

  if (!$B_wartung_start)
  {
  echo "Das Forum ist momentan noch nicht gesperrt. Dies ist zur Benutzung des Wartungssystems
  notwendig.
  
  <form action=\"wartung-aktivieren.php\" method=\"post\">
    <table  cellspacing=\"40\">
      <tr>
        <td align=\"center\">
          Begin der Wartung in:<br>
          <select name=\"start\">
            <option>5 Minuten</option>
            <option>10 Minuten</option>
            <option>15 Minuten</option>
            <option>30 Minuten</option>
            <option>45 Minuten</option>
            <option>60 Minuten</option>
          </select>
        </td>
        <td align=\"center\">
          Dauer der Wartung:<br>
          <select name=\"dauer\">
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
  </form>";
  }
  else
  {
    echo "Das Forum ist momentan gesperrt<p>
    
  folgende Wartungsmodule stehen zur Verf&uuml;gung:<p>"; 

    $handle = opendir ("module");
    $verz = array ();
    while ($datei = readdir ($handle))
      if (strpos (ltrim ($datei)))
        array_push ($verz, $datei);
    closedir ($handle);

    reset ($verz);
    while ($datei = current ($verz))
    {
      echo "$datei<br>";
      next ($verz);
    }
  }
?>
