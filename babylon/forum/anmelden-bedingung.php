<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

include '../gemeinsam/msie.php';
$msiepng = msie_png ();
include 'konf/konf.php';
include 'konf/anmelden-bedingung.dat';

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"
     \"http://www.w3.org/TR/html4/loose.dtd\">
<html>
<head>
<link href=\"/forum/stil/$B_standart_stil.css\" rel=\"stylesheet\" type=\"text/css\">
<title>$B_betreiber / Forum Anmeldung</title>
</head>
<body>";

anmelden_bedingung ();

echo "  <img src=\"/grafik/dummy.png\" width=\"1\" height=\"40\" border=\"0\" alt=\"\">
  <table>
    <tr>
      <td><img src=\"/grafik/dummy.png\" width=\"40\" height=\"1\" border=\"0\" alt=\"\"></td>
      <td>
        <form action=\"bedingung-test.php\" method=\"post\">
        <input type=\"checkbox\" name=\"gelesen\">Ich habe die kompletten Bedingungen gelesen<p>
        <input type=\"checkbox\" name=\"verstanden\">Ich habe den Inhalt der Bedingungen verstanden<p>
        <input type=\"checkbox\" name=\"zustimmen\">Ich stimme dem Bedingungen zu<p>
        <button><img src=\"/grafik/PfeilRechts$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Weiter</button>
	</form>
      </td>
    </tr>
  </table>
</body>
</html>";

