<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

include "../gemeinsam/msie.php";
$msiepng = msie_png ();
include "konf/konf.php";
include "konf/anmelden-bedingung.dat";

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
<html>
<head>
<link href=\"/forum/stil/std.css\" rel=\"stylesheet\" type=\"text/css\">
<title>$B_betreiber / Forum Anmeldung</title>
</head>
<body>";

anmelden_bedingung ();

echo "  <img src=\"/grafik/dummy.png\" width=\"1\" height=\"40\" border=\"0\" alt=\"\">
  <table>
    <tr>
      <td><img src=\"/grafik/dummy.png\" width=\"40\" height=\"1\" border=\"0\" alt=\"\"></td>
      <td><form action=\"bedingung-test.php\" method=\"post\">
        <input type=\"checkbox\" name=\"gelesen\">Ich habe die kompletten Bedingungen gelesen</input><p>
        <input type=\"checkbox\" name=\"verstanden\">Ich habe den Inhalt der Bedingungen verstanden</input><p>
        <input type=\"checkbox\" name=\"zustimmen\">Ich stimme dem Bedingungen zu</input><p>
        <button name=\"gehe_zu\" value=\"$_POST[gehe_zu]\"><img src=\"/grafik/PfeilRechts$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Weiter</button>
      </td>
    </tr>
  </table>
</body>
</html>";

