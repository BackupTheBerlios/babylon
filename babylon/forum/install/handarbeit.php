<?php;
/* Copyright 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

echo '<h1>Handarbeit</h1>
  Jetzt ist ein wenig Handarbeit angesagt. Da aus Sicherheitsgr&uuml;nden der Webserver und somit
  auch PHP nicht schreibend auf das Dateisystem zugreifen d&uuml;rfen mu&szlig;t Du ein paar
  Variablen von Hand setzen (wenn sie es d&uuml;rfen solltest Du nochmals Deine
  Sicherheitseinstellungen &uuml;berdenken).<p>

  &Ouml;ffne hierzu die Datei <tt>gemeinsam/db-verbinden.php</tt> der Babylon-Installation mit
  einem Texteditor. In der Funktion <tt>db_verbinden</tt> werden diekt am Anfang vier Variablen
  deklariert.<p>

  <tt>host</tt> Der Rechner auf dem die Datenbank l&auml;uft<br>
  <tt>nutzer</tt> Der Benutzer in dessen Namen Babylon auf die Datenbank zugreifen soll<br>
  <tt>passwort</tt> Das Datenbankpasswort f&uuml;r <i>nutzer</i><br>
  <tt>datenbank</tt> Der Name der Datenbank die Babylon nutzen soll<p>

  Trage in diese Variablen die Werte ein und dann gehts...
<form action="admin-anlegen.php" method="post">
  <button >Weiter</button>
</form>';
;?>

