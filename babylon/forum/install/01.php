<?php;
echo '<h2>Datenbank</h2>

  Ist auf Deinem System schon eine Datenbank vorhanden, die Du nutzen willst (oder mu&szlig;t weil
  sie z.B. von Deinem Webspace Provider vorgegeben ist)?<p>

<form action="02.php" method="post">
  <input type="radio" name="datenbank" value="vorhanden">Es ist eine Datenbank vorhanden</input><p>
</form>
<form action="01a.php" method="post">
  <input type="radio" name="datenbank" value="anlegen">Es soll eine neue Datenbank mit folgenden
    Namen angelegt werden</input><br>
  Datenbankname: <input type="text" name="dbname"></input><p>

  
  
  <button >Weiter</button>
</form>';
;?>

