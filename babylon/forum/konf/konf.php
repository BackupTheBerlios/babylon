<?PHP;
  $verbindung = $_SERVER['DOCUMENT_ROOT'] . '/gemeinsam/db-verbinden.php';
  include_once ($verbindung);
  $db = db_verbinden ();    

  $erg = mysql_query ("SELECT Schluessel, WertInt, WertText, Typ
                       FROM Konf")
    or die ('Die Konfiguration konnte nicht gelesen werden');

  while ($zeile = mysql_fetch_row ($erg))
  {
    if ($zeile[3] == 'i' or $zeile[3] == 'f')
      ${$zeile[0]} = $zeile[1];
    else if ($zeile[3] == 'b')
      ${$zeile[0]} = $zeile[1] ? TRUE : FALSE;
    else if ($zeile[3] == 't')
      ${$zeile[0]} = $zeile[2];
    else if ($zeile[3] == 'a')
      ${$zeile[0]} = explode (',', $zeile[2]);
    else
      die ('Die Datenbank enth&auml;lt einen nicht unterst&uuml;tzten Datentyp');
  }
?>
