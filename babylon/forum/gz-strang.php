<?php
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"
  \"http://www.w3.org/TR/html4/loose.dtd\">
<html>
<head>
  <script type=\"text/javascript\">
    <!--
    window.location.href = \"beitraege.php?fid=$fid&tid=$tid&sid=-1&bid=$bid\";
    //-->
  </script>
      
  <noscript>
    <meta http-equiv=\"refresh\" content=\"0; URL=beitraege.php?fid=$fid&tid=$tid&sid=-1&bid=$bid\">
  </noscript>
  <title></title>
</head>
<body>
  <a href=\"beitraege.php?fid=$fid&tid=$tid&sid=-1&bid=$bid\">Weiter...</a>
</body>
</html>";
?>
