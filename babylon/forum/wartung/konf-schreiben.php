<?PHP;
function konf_schreiben ($var, $wert)
{
  global $K_AdminForen;

  if (!$K_AdminForen)
    die ("Zugriff verweigert");

  $fd = fopen ("../konf/konf.php", "r+");

  $pos = 0;
  $gefunden = FALSE;

  while ($zeile = fgets ($fd, 1000))
  {
    $pat =  "/^.$var\s?=\s?\d+;.*/";
    if (preg_match ($pat, $zeile))
    {
      $gefunden = TRUE;
      break;
    }
    $pos = ftell ($fd);
  }
  
  if (!$gefunden)
    die ("Zu schreibende Variable konnte in der Konfigurationsdatei nicht gefunden werden.");

  fseek ($fd, $pos);
  $zeile = "\$$var = $wert;";
  $zeile = str_pad ($zeile, 63, ' ');
  fputs ($fd, $zeile);
  fclose ($fd);
}
;?>
