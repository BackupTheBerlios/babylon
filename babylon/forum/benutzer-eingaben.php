<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function manipulation ()
{
  die ('Entweder Dein Browser ist defekt und &uuml;bergtr&auml;gt falsche Daten oder Du versuchst das System zu manipulieren');
}

function benutzer_eingabe_test ($var_tmp, $var_text, $min, $max, $fehler, $leer)
{
  
  $var = utf8_encode ($_POST[$var_tmp]);

  if (!strlen ($var) && $leer)
    return false;
  
  if (strlen ($var) < $min)
  {
    if (strlen ($fehler))
      echo '<h2>' . $fehler . '</h2><p>';
    return true;
  }
  if (strlen ($var) > $max)
    manipulation ();
  if (ereg ('[^[[:alnum:]] _-]*', $var))
  {
    echo "<h2>In den Eingaben d&uuml;rfen ausschlie&szlig;lich Buchstaben,
         Ziffern, Leerzeichen und der Binde-, bzw. Unterstrich vorkkommen<br>
         Bitte $var_text korrigieren";
    return true;
  }
  return false;
}

function email_adresse_gueltig ($adresse)
{
  return preg_match ('/^[a-z0-9]+[a-z0-9._-]*@([a-z0-9_-]{2,}\.)+[a-z]{2,}/', $adresse);
}
;?>
