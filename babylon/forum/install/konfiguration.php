<?PHP;
 /* Copyright 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

echo '    Das System ist jetzt schon voll Lauff&auml;hig. Wir passen es im folgenden an Deine
    Bed&uuml;rfnisse an. Hierf&uuml;r rufen wir das Wartunssystem auf, das Du f&uuml;r
    sp&auml;tere Anpassungen unter <tt>forum/wartung/wartung.php</tt> findest.<p>

    Im <a href="../wartung/wartung.php">Wartungssystem</a> aktiviere zuerst das Wartunssystem
    selbst und rufe dann das Wartunsmodul "Systemvariablen". Dort findest Du diverse
    Bezeichner, mit denen Du einige Grunds&auml;tzliche Verhaltensweisen des Forums
    einstellen kannst. In den meisten F&auml;llen sollte es ausreichen, die erste vier
    Variablen anzupassen. Danach beende das Wartunssystem und kehre hier hin zur&uuml;ck.<p>
   
    Bearbeite jetzt die Datei <tt>forum/konf/anmelden-bedingung.dat</tt>. In dieser
    kannst Du die Nutzungsbedingungen f&uuml;r Dein Forum ablegen, die wenn sich neue
    Besucher anmeden wollen angezeigt werden.<p>
   
    Gehe dann ins <a href="../forum-anlegen.php">Forum und lege die Unterforen</a> an.
    Hier wird Dir eine Liste mit den 32 zur verf&uuml;gung stehenden Foren angezeigt. Gib den
    Titel des Forums und eine kurze Beschreibung an und w&auml;hle dann aus, an welcher
    Position das Forum stehen soll. Du solltest Dir als vorher &uuml;berlegen, welche Foren
    Du anlegen willst. Lege diese dann am besten so ab, dass zwischen den Foren freie
    Pl&auml;tze bestehen bleiben um sp&auml;ter weitere Unterforen "dazwischen schieben" zu
    k&ouml;nnen (momentan ist es noch nicht m&ouml;glich bestehende Foren in der Position
    zu verschieben). Alle ungenutzten Foren werden sp&auml;ter in der Foren&uml;bersich nicht
    angezeigt.<p>
    
    Das wars :-)<p>

    Du solltest bevor Du Deinen Webserver und damit das Forum von aussen erreichbar machst
    das komplette Installationssystem (das Verzeichniss <tt>install/</tt> mit allen Dateien)
    vom Webserver entfernen.<p>

    Viel Spass<br>
    detlef';
;?>
