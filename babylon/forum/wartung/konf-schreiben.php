<?PHP;
function konf_schreiben ($var, $wert)
{
  global $K_AdminForen;

  if (!$K_AdminForen)
    die ("Zugriff verweigert");

  $fd = fopen ("../konf/konf-test.php", "r+");

  $pos = 0;

  while ($zeile = fgets ($fd, 300))
  {
    echo "$zeile<br>";
    if (!strpos ($zeile, $var))
      break;
    $pos = ftell ($fd);
  }
  if (!isset ($zeile))
    die ("Zu schreibende Variable konnte in der Konfigurationsdatei nicht gefunden werden.");

  $pos -= strlen ($zeile);
  fseek ($fd, $pos);
  
  $zeile = "$var = $wert";
  fputs ($fd, $zeile);
  fclose ($fd);
}
;?>
