<?php;
/* Copyright 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

echo '<h2>Datenbank</h2>

  Ist auf Deinem System schon eine Datenbank vorhanden, die Du nutzen willst (oder mu&szlig;t weil
  sie z.B. von Deinem Webspace Provider vorgegeben ist) oder soll eine neue Datenbank erstellt werden?<p>

  <form action="datenbank-einrichten.php" method="post">
    <input type="radio" name="datenbank" value="vorhanden">Es ist eine Datenbank vorhanden</input><br>
    <input type="radio" name="datenbank" value="anlegen" checked>Es soll eine neue Datenbank
      angelegt werden</input><p>
    Auch wenn die Datenbank schon vorhanden ist sind die folgenden Felder auszuf&uuml;llen,
    damit die notwendigen Tabellen in dieser Datenbank erstellt werden k&ouml;nnen.
    Der Namen einer zu erstellenden Datenbank, das Passwort und der Datenbankbenutzer darf
    nur aus Alpha-Nummerischen Zeichen und dem Binde- bzw. Unterstrich bestehen.
    Die maximale L&auml;nge ist jeweils auf maximal 64 Zeichen beschr&auml;nkt.<p>

    <table>
      <tr>
        <td>Name:</td>
        <td><input type="text" name="dbname" value="babylon"></input></td>
        <td>Der Name der zu erstellenden Datenbank</td>
      </tr>
      <tr>
        <td>Host:</td>
        <td><input type="text" name="host" value="localhost"></input></td>
        <td>Der Host auf dem die Datenbank l&auml;uft</td>
      </tr>
      <tr>
        <td>Benutzer:</td>
        <td><input type="text" name="benutzer"></input></td>
        <td>Der Benutzername f&uuml;r die Datenbankerstellung</td>
      </tr>
      <tr>
        <td>Passwort:</td>
        <td><input type="password" name="passwort"></input></td>
        <td>Das Passwort f&uuml;r die Datenbankerstellung</td>
      </tr>
    </table>
    <p>
    <button type="submit"> Weiter &gt; </button>
  </form>';

;?>
