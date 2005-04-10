<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */


// wir vergleichen ob der parameter der von einem string zu einem int
// und wieder zurueck zu eime string gewandelt wurde vor und nach der
// umwandlung gleich ist. so entlarven wir den versuch parameter ein-
// zuschleusen.
// fid == forum id
// tid == thema id
// sid == strang id
// bid == beitrag id
// zid == zitat id
// neu == neuer beitrag

function id_von_get_post (&$fid, &$tid, &$sid, &$bid, &$zid, &$neu)
{
  $var = array ('fid', 'tid', 'sid', 'bid', 'zid', 'neu');
  foreach ($var as $id)
  {
    if (isset ($_POST["$id"]))
    {
      $intid = intval ($_POST["$id"]);
//      if (strcmp (strval ($intid), $_POST[$id]) or $intid < -1)
//        return true;
      $ID = $id;
      $$ID = $intid;
    }
    else if (isset ($_GET["$id"]))
    {
      $intid = intval ($_GET["$id"]);
//      if (strcmp (strval ($intid), $_GET[$id]) or $intid < -1)
//        return true;
      $ID = $id;
      $$ID = $intid;
    }
  }
  return false;
}   
?>
