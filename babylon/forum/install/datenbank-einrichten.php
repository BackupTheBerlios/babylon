<?php;
/* Copyright 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function server_verbinden ($host, $benutzer, $passwort)
{
  echo 'Datenbankserver verbinden... ';

  mysql_connect ($host, $benutzer, $passwort)
    or die ('Verbindung mit dem Datenbankserver ist fehl geschlagen!');

  echo 'OK<br>';
}
function datenbank_auswaehlen ($dbname)
{
  echo 'Datenbank ausw&auml;hlen... ';

  mysql_select_db ($dbname)
    or die ("Die Auswahl der Datenbank $dbname ist fehl geschlagen");

  echo 'OK<br>';
}

function tabellen_erstellen ()
{
  $tabellen = array ('Beitraege', 'Benutzer', 'BenutzerVorlage', 'Konf');
  
  while ($tabelle = current ($tabellen))
  {
    echo "Tabelle $tabelle erstellen... ";
    if (!is_readable ('dat/' . $tabelle . '.dat'))
      die ("Der Zugriff auf die Tabellendefinition $tabelle.dat wurde verweigert");
    $daten = implode ('', file ('dat/' . $tabelle . '.dat'));
    mysql_query ($daten)
      or die ('Fehlgeschlagen!<br>' . mysql_error ());
    echo 'OK<br>';
    next ($tabellen);
  }

  reset ($tabellen);

  $tabellen = array ('Beitraege', 'Konf', 'BenutzerVorlage');
  while ($tabelle = current ($tabellen))
  {
      echo "Tabelle $tabelle mit Startwerten f&uuml;llen: ";
    if (!is_readable ('dat/' . $tabelle . 'Inhalt.dat'))
      die ('Der Zugriff auf die Tabelleninahlaltsdefinition ' . $tabelle . 'Inhalt.dat wurde verweigert');
    $daten = file ('dat/' . $tabelle . 'Inhalt.dat');
    while ($satz = current ($daten))
    {
      mysql_query ($satz)
        or die ('Fehlgeschlagen!<br>' . mysql_error ());   
      echo '.';
      next ($daten);
    }
    echo ' OK<br>';
    next ($tabellen); 
  }
}


if (! (isset ($_POST['datenbank']) && strcmp ($_POST['datenbank'], 'vorhanden') == 0))
{
  if ((!isset ($_POST['dbname']))
       || (!isset ($_POST['host']))
       || (!isset ($_POST['benutzer']))
       || (!isset ($_POST['passwort'])))
  {
    die ('Du mu&szlig;t den Namen der zu erstellenden Datenbank angenben.<br>
          Des weiteren musst Du das Passwort und den Benutzer angeben, der berechtigt ist
          die Datenbank zu erstellen und den Host auf dem das Datenbanksystem l&auml;uft.
      <form action="datenbank-auswaehlen.php" method="post">
        <button type="submit"> Zur&uuml;ck &lt; </button>
      </form>');
  }

  $dbname = $_POST['dbname'];
  $host = $_POST['host'];
  $benutzer = $_POST['benutzer'];
  $passwort = $_POST['passwort'];
    
  if (
       (! (preg_match ('/^[a-zA-Z0-9_-]{1,64}$/', $dbname)))
        || (! (preg_match ('/^[a-zA-Z0-9_-]{1,64}$/', $benutzer)))
        || (! (preg_match ('/^[a-zA-Z0-9_-]{1,64}$/', $passwort))))
  {
    die ('Der Namen der zu erstellenden Datenbank, das Passwort und der Datenbankbenutzer darf
          nur aus Alpha-Nummerischen Zeichen und dem Binde- bzw. Unterstrich bestehen.
          Die maximale L&auml;nge ist jeweils auf maximal 64 Zeichen beschr&auml;nkt.
      <form action="datenbank-auswaehlen.php" method="post">
        <button type="submit"> Zur&uuml;ck &lt; </button>
      </form>');
  }

  server_verbinden ($host, $benutzer, $passwort);

  echo "Datenbank $dbname erstellen... ";
  mysql_query ("CREATE DATABASE $dbname");
  if (mysql_errno ())
  {
    $e = mysql_error ();
    echo "Die Datenbank $dbname konnte nicht erstellt werden<p>
     $e<p>
      <form action=\"datenbank-auswaehlen.php\" method=\"post\">
        <button type=\"submit\"> Zur&uuml;ck &lt; </button>
      </form>";
     die ();
  }
  else
    echo 'OK<br>';
}
else
  server_verbinden ($_POST['host'], $_POST['benutzer'], $_POST['passwort']);

datenbank_auswaehlen ($_POST['dbname']);
tabellen_erstellen ();
 
     
echo '<form action="handarbeit.php" method="post">
  <button > Weiter &gt; </button>
</form>';
;?>

