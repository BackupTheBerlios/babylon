<?php
/* Copyright 2003, 2004, 2005 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function beitrag_pharsen_smilies_quoted ($text)
{
  return strtr ($text, array(':-)' => '<img src=\"smileys/smile.png\" alt=\":-)\">',
                             ';-)' => '<img src=\"smileys/wink.png\" alt=\";-)\">',
                             ':-D' => '<img src=\"smileys/biggrin.png\" alt=\":-D\">',
                             ':-(' => '<img src=\"smileys/frown.png\" alt=\":-(\">',
                             ':-P' => '<img src=\"smileys/tongue.png\" alt=\":-P\">',
                             'B-)' => '<img src=\"smileys/glasses.png\" alt=\"B-)\">',
                             ':-/' => '<img src=\"smileys/wrygrin.png\" alt=\":-/\">',
                             ':-|' => '<img src=\"smileys/neutral.png\" alt=\":-|\">',
                             ':o)' => '<img src=\"smileys/bignose.png\" alt=\":o)\">',
                             'P-)' => '<img src=\"smileys/pirate.png\" alt=\"P-)\">'));
}

function beitrag_pharsen_smilies ($text)
{
  return strtr ($text, array(':-)' => '<img src=\"smileys/smile.png\" alt=\":-)\">',
                             ';-)' => '<img src=\"smileys/wink.png\" alt=\";-)\">',
                             ':-D' => '<img src=\"smileys/biggrin.png\" alt=\":-D\">',
                             ':-(' => '<img src=\"smileys/frown.png\" alt=\":-(\">',
                             ':-P' => '<img src=\"smileys/tongue.png\" alt=\":-P\">',
                             'B-)' => '<img src=\"smileys/glasses.png\" alt=\"B-)\">',
                             ':-/' => '<img src=\"smileys/wrygrin.png\" alt=\":-/\">',
                             ':-|' => '<img src=\"smileys/neutral.png\" alt=\":-|\">',
                             ':o)' => '<img src=\"smileys/bignose.png\" alt=\":o)\">',
                             'P-)' => '<img src=\"smileys/pirate.png\" alt=\"P-)\">'));
}

function beitrag_pharsen_ohne_smilies ($text)
{
  // wir koennen hier nich nl2br verwenden, da es nur mit \n aber
  // nicht mit \r\n arbeitet...
  return str_replace (array ("\r\n", "\r", "\n"), '<br />', strip_tags ($text, '<b><i><tt><pre><div><span><table><tr><td><ul><ol><li><img><h1><h2><h3><h4><h5><h6><br><p>'));
}

function beitrag_pharsen ($text)
{
  // muss in dieser reihenfolge passieren, damit wir externe bilder raus
  // bekommen, unsere smilies aber erhalten bleiben
  return (beitrag_pharsen_smilies_quoted (beitrag_pharsen_ohne_smilies ($text)));
}

?>
