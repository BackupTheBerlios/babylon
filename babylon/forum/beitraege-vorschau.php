<?PHP;
/* Copyright 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function beitrag_vorschau_inhalt ($BenutzerId)
{
  $erg = mysql_query ("SELECT Ablage
                       FROM Benutzer
                       WHERE BenutzerId = $BenutzerId")
    or die ('F0031: Zwischenablage konnte nicht gelesen werden');
  $zeile = mysql_fetch_row ($erg);
  return $zeile[0];
}


function beitrag_vorschau (&$beitrag)
{
        
  echo '             <a name="vorschau_bereich"></a>
       <tr>
          <td>
            <table width="100%" border="1" cellpadding="10">
              <tr>
                <td background="/grafik/vorschau-hintergrund.png" width="100%" class="vorschau">';
          echo stripslashes (beitrag_pharsen_ohne_smilies ($beitrag));
          echo '                </td>
              </tr>
            </table><br>
            <img src="/grafik/dummy.png" width="18" height="1" border="0" alt="">
          </td>
        </tr>';
}

function beitrag_vorschau_textarea (&$beitrag)
{
  echo str_replace ("&", "&amp;", stripslashes (str_replace ("<br />", "\n", $beitrag)));
}
?>
