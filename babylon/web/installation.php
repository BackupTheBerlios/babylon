<?PHP;
echo "  <a name=\"installation\"><img Name=\"b7\" src=\"/web/grafik/links02.png\"></a><p>
  <h3>Daten einspielen</h3>
  Sollten irgendwelche Probleme bei der Installation /Konfiguration auftreten, schreib mir und
  versuch den Fehler m&ouml;glichst detaiert zu beschreiben.<p>
  
  Mit allen Angaben die ich zur Konfiguration mache gehe ich davon aus, dass Du das Forum lokal auf
  Deinem Rechner (Unixoides System, z.B. Linux) installierst. Alles andere w&auml;re zu mindest
  f&uuml;r den Anfang leichtsinn!<p>

  Kopiere rekursiv die Verzeichnisse <tt>forum, grafik und gemeinsam</tt> in Dein Web-Root Verzeichniss
  (bei Apache findet es sich in der <tt>/etc/apache/http.conf</tt> unter dem Schl&uuml;ssel
  \"DocumentRoot\").
  
  <h3>Einrichtung der Datenbank</h3>
  Danach ist die MySQL-Datenbank f&uuml;r Babylon anzulegen. Dies ist momentan noch Handarbeit. Am
  einfachsten geht es mit phpmyadmin, wenn Du dort in der SQL-Konsole folgendes eingiebst:<p>
  <pre>
CREATE DATABASE `babylon`;
USE babylon;

CREATE TABLE `Beitraege` (
  `BeitragTyp` tinyint(1) unsigned default '0',
  `ForumId` int(1) unsigned default '0',
  `ThemaId` int(1) unsigned default '0',
  `StrangId` int(1) unsigned default '0',
  `BeitragId` int(1) unsigned NOT NULL auto_increment,
  `NumBeitraege` mediumint(1) unsigned default '0',
  `NumGelesen` int(1) unsigned default '0',
  `Gesperrt` char(1) NOT NULL default 'n',
  `Titel` varchar(255) NOT NULL default '',
  `Autor` varchar(32) NOT NULL default '',
  `AutorLetzter` varchar(32) NOT NULL default '',
  `StempelStart` int(1) unsigned default '0',
  `StempelLetzter` int(1) unsigned default '0',
  `Eltern` int(1) unsigned default '0',
  `WeitereKinder` char(1) NOT NULL default 'n',
  `Inhalt` mediumtext,
  PRIMARY KEY  (`BeitragId`)
) TYPE=MyISAM AUTO_INCREMENT=252 ;

CREATE TABLE `Benutzer` (
  `BenutzerId` mediumint(1) unsigned NOT NULL auto_increment,
  `Benutzer` varchar(32) default NULL,
  `VName` varchar(32) default NULL,
  `NName` varchar(32) default NULL,
  `Cookie` varchar(32) default NULL,
  `Eingeloggt` char(1) default 'n',
  `Passwort` varchar(32) default NULL,
  `PassTmp` varchar(32) default NULL,
  `PassTmpStempel` int(1) default NULL,
  `PassTmpIP` varchar(16) default NULL,
  `PassUngueltig` char(1) NOT NULL default 'n',
  `EMail` varchar(255) default NULL,
  `Anmeldung` int(1) unsigned default NULL,
  `Mitglied` char(1) default 'n',
  `Beitraege` mediumint(1) unsigned default '0',
  `Themen` mediumint(1) unsigned default '0',
  `LetzterBeitrag` int(1) unsigned default '0',
  `RechtLesen` int(1) unsigned default '4294967295',
  `RechtSchreiben` int(1) unsigned default '4294967295',
  `RechtAdmin` int(1) unsigned default '0',
  `RechtAdminForen` int(1) unsigned default '0',
  `KonfThemenJeSeite` tinyint(1) unsigned default '6',
  `KonfBeitraegeJeSeite` tinyint(1) unsigned default '3',
  `KonfStil` varchar(16) default 'std',
  `Atavar` char(1) NOT NULL default 'n',
  `KonfSignatur` varchar(255) default NULL,
  `KonfSprungSpeichern` smallint(1) unsigned default '0',
  `KonfBaumZeigen` char(1) NOT NULL default 'j',
  `ProfilNameZeigen` char(1) NOT NULL default 'n',
  `ProfilEMail` varchar(255) default NULL,
  `ProfilHomepage` varchar(255) default NULL,
  `ProfilOrt` varchar(255) NOT NULL default '',
  `Ablage` mediumtext,
  PRIMARY KEY  (`BenutzerId`,`BenutzerId`)
) TYPE=MyISAM AUTO_INCREMENT=12 ;
</pre><p>
  Damit sind die ben&ouml;tigten Tabellen angelegt.<p>

  Trage jetzt in die Datei <tt>/gemeinsam/db-verbinden.php</tt> in den Aufruf <tt>mysql_connect</tt>
  als zweiten Parameter den Benutzer f&uuml;r diese Datenbank und als dritten Parameter das
  Datenbankpasswort f&uuml;r den Benutzer ein.<p>
  
  <h3>Forenadministrator anlegen</h3>
  Anschlie&szlig;end folgt die Erstellung des
  ersten Benutzers, der Forenadministrationsrechte bekommen wird. Begib dich hierzu mit dem Browser
  auf <tt>http://localhost/forum/foren.php</tt> und klicke auf die \"Anmelden\"-Schaltfl&auml;che.
  Dann folge einfach den Dialogen was Dich zu guter Letzt wieder auf die Forenseite bringen sollte.
  Jetzt musst Du nochmals phpmyadmin zu Hand nehmen um dem angelegten Benutzer die
  Forenadministrationsrechte zu geben. W&auml;hle hierzu die Datenbank <tt>babylon</tt> aus und lass Dir
  dann die Datens&auml;tze von <tt>Benutzer</tt> anzeigen. Der einzige Datensatz ist der von dem
  Benutzer, den Du grade angelegt hast. Bearbeite ihn insofern, dass Du <tt>RechtAdminForen</tt> auf
  <tt>1</tt> setzt.<p>

  <h3>Foren-Grundger&uuml;st anlegen</h3>
  Jetzt musst Du die initialen Datens&auml;tze erstellen. Logge Dich hierzu erst ins Forum ein und
  rufe dann im Browser <tt>http://localhost/forum/foren-anlegen-start.php</tt> auf. Hiermit werden
  Datens&auml;tze f&uuml;r die anschliessend zu erstellende Foren reserviert.<p>
  
  <h3>Foren anlegen</h3>
  Um die einzelnen Foren anzulegen rufe <tt>http://localhost/forum/forum-anlegen.php</tt> auf. Hier ist
  der Name des Forums und ein Kurze Beschreibung anzugeben. W&auml;hle aus an welcher Position
  das Forum abgelegt werden soll. Es stehen Dir insgesamt 32 Positionen zur Verf&uuml;gung. In der
  Reihenfolge in der Du deine Foren hier ablegst werden sie anschliessen auf der Forenseite
  dargestellt. Momentan ist es noch nicht m&ouml;glich ein einmal auf einer Positoion
  eingerichtetes Forum zu verschieben. Folglich ist es am besten zwischen den einzelnen Foren
  jeweils ein paar Stellen aus zu lassen. Diese werden in der Forendarstellung nicht angezeigt und
  stehen Dir sp&auml;ter f&uuml;r Erweiterungen zur Verf&uuml;gung.<p>

  <h3>Konfiguration</h3>
  Im Verzeichnisss <tt>/forum/konf/</tt> stehen Dir einige M&ouml;glichkeiten zur Anpassung zur
  Verf&uuml;gung. In der Datei <tt>anmelden-bedingung.dat</tt> kannst Du eingene eigene
  Bedingungen f&uuml;r die Anmeldung in Deinem Forum definieren. Die Datei <tt>konf.php</tt>
  enth&auml;lt einige Konfigurationsvariablen die noch richtig zu setzen sind:
  <ul>
    <li><b>B_betreiber</b> gibt den Namen des Projekts an, auf dem Du das Forum eingebunden hast</li>
    <li><b>B_startseite_link</b> Der Hilfetext, der beim &Uuml;berfahren des Logos in der linken
           oberen Ecke angezeigt wird</li>
    <li><b>B_seitentitel_start</b> Dieser Text wird bei allen Seiten an den Anfang des Textes
           gesetzt, der im Titel des Browsers angezeigt wird</li>
    <li><b>B_mail_absender</b> Wenn Babylon Mails verschickt, z.B. weil ein Benutzer sein Passwort
           vergessen hat, wird diese Adresse als Absender genommen</li>
  </ul>
  Die restlichen Schl&uuml;ssel sind f&uuml;r die Anf&auml;ngliche Konfiguration nicht so wichtig.
  F&uuml;r weitergehende Informationen lies die Dokumentation.<p>

  <b>Herzlichen Gl&uuml;ckwunsch! Dein Forum sollte jetzt so weit sein, dass es genutzt werden kann</b><p>";
;?>
