<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function beitrag_pharsen_smilies_quoted ($text)
{
  $array_1 = array(':-)', ';-)', ':-D', ':-(', ':-P', 'B-)', ':-/', ':-|',
                   ':o)', 'P-)');
  $array_2 = array('<img src=\"smileys/smile.png\" alt=\":-)\">',
                   '<img src=\"smileys/wink.png\" alt=\";-)\">',
                   '<img src=\"smileys/biggrin.png\" alt=\":-D\">',
                   '<img src=\"smileys/frown.png\" alt=\":-(\">',
                   '<img src=\"smileys/tongue.png\" alt=\":-P\">',
                   '<img src=\"smileys/glasses.png\" alt=\"B-)\">',
                   '<img src=\"smileys/wrygrin.png\" alt=\":-/\">',
                   '<img src=\"smileys/neutral.png\" alt=\":-|\">',
                   '<img src=\"smileys/bignose.png\" alt=\":o)\">',
                   '<img src=\"smileys/pirate.png\" alt=\"P-)\">',
                   );
  for($x = 0; $x < 10; $x++)
    $text = str_replace($array_1[$x], $array_2[$x], $text);
  return $text;
}

function beitrag_pharsen_smilies ($text)
{
  $array_1 = array(':-)', ';-)', ':-D', ':-(', ':-P', 'B-)', ':-/', ':-|',
                   ':o)', 'P-)');
  $array_2 = array('<img src="smileys/smile.png" alt=":-)">',
                   '<img src="smileys/wink.png" alt=";-)">',
                   '<img src="smileys/biggrin.png" alt=":-D">',
                   '<img src="smileys/frown.png" alt=":-(">',
                   '<img src="smileys/tongue.png" alt=":-P">',
                   '<img src="smileys/glasses.png" alt="B-)">',
                   '<img src="smileys/wrygrin.png" alt=":-/">',
                   '<img src="smileys/neutral.png" alt=":-|">',
                   '<img src="smileys/bignose.png" alt=":o)">',
                   '<img src="smileys/pirate.png" alt="P-)">',
                   );
  for($x = 0; $x < 10; $x++)
    $text = str_replace($array_1[$x], $array_2[$x], $text);
  return $text;
}

function beitrag_pharsen_ohne_smilies ($text)
{
  // wir koennen hier nich nl2br verwenden, da es nur mit \n aber
  // nicht mit \r\n arbeitet...
  return str_replace (array ("\r\n", "\r", "\n"), '<br />', strip_tags ($text, '<b><i><tt><pre><div><table><tr><td><ul><ol><li><img><h1><h2><h3><h4><h5><h6><br><p>'));
}

function beitrag_pharsen ($text)
{
  // muss in dieser reihenfolge passieren, damit wir externe bilder raus
  // bekommen, unsere smilies aber erhalten bleiben
  return (beitrag_pharsen_smilies_quoted (beitrag_pharsen_ohne_smilies ($text)));
}

?>
