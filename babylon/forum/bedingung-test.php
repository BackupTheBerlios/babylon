<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  if(isset ($_POST[gelesen]) == FALSE or
     isset ($_POST[verstanden]) == FALSE or
     isset ($_POST[zustimmen]) == FALSE)
  {
    echo "<h2>Du musst den Bedingungen zustimmen um Dich im Forum anmelden zu k&ouml;nnen</h2><p>";
    include("anmelden-bedingung.php");
  }
  else
  {
    echo "<!--DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\"-->
          <html><head>
          <meta http-equiv=\"refresh\" content=\"0; URL=anmelden.php\">
          <title></title></head><body>
          <a href=\"anmelden.php\">Weiter...</a>
          </body></html>";
  }
?>  
