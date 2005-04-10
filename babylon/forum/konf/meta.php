<?php
/* Copyright 2003, 2004, 2005 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function metadata ($datei)
{
$stempel = date ("Y-m-j G:i", filectime ($datei));
echo"    <meta name=\"author\" content=\"niemand\">
    <meta name=\"keywords\" lang=\"de\" content=\"Babylon Forum\">
    <meta name=\"date\" content=\"$stempel\">
    <meta name=\"robots\" content=\"index\">
    <meta name=\"robots\" content=\"follow\">
    <meta http-equiv=\"content-type\" content=\"text/html; charset=ISO-8859-1\">
    <meta http-equiv=\"content-language\" content=\"de\">
    <meta http-equiv=\"expires\" content=\"0\">
    <link rel=\"shortcut icon\" href=\"/grafik/favicon.ico\">\n";
}
?>
