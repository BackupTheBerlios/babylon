<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
  "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

if (isset ($vorschau) and strcmp ($vorschau, "ja") == 0)
  $vb = "#vorschau_bereich";
else
  $gz = "";
if (isset ($zid))
  $textarea = "#textarea";

echo "<script type=\"text/javascript\">
      <!--
      window.location.href = \"beitraege.php?fid=$fid&tid=$tid&sid=$sid&bid=$bid&zid=$zid&titel=$titel&vorschau=$vorschau&neu=$neu$textarea\";
      //-->
      </script>
      
      <noscript>
      <meta http-equiv=\"refresh\" content=\"0; URL=beitraege.php?fid=$fid&tid=$tid&sid=$sid&bid=$bid&zid=$zid&titel=$titel&vorschau=$vorschau&neu=$neu$textarea\">
      <title></title></head><body>
      <a href=\"beitraege.php?fid=$fid&tid=$tid&sid=$sid&bid=$bid&zid=$zid&titel=$titel&vorschau=$vorschau&neu=$neu$textarea\">Weiter...</a>
      </noscript>";      
?>
</body>
</html>
