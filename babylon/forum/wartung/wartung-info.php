<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

$stempel = time ();

if ($B_wartung_start and $B_wartung_start < $stempel)
{
  if ($B_wartung_ende <= $stempel)
    $geschlossen_bis = 'wenigen Augenblicken';
  else
  {
    $min = ceil (($B_wartung_ende - $stempel) / 60);
    if ($min >= 60)
    {
      $std = floor ($min / 60);
      $min = $min % 60;

      if ($std == 1)
        $s = 'Stunde';
      else
        $s = 'Stunden';

      if ($min == 1)
        $m = 'Minute';
      else
        $m = 'Minuten';

      $geschlossen_bis = "$std $s $min $m";
    }
    else
    {
      if ($min < 2)
        $geschlossen_bis = 'wenigen Augenblicken';
      else
       $geschlossen_bis = "$min Minuten"; 
    }
  }

  die ("<h1>Wartungsarbeiten</h1>
  Das Forum ist f&uuml;r Wartungsarbeiten geschlossen.<p>
  Es wird voraussichtlich in $geschlossen_bis wieder er&ouml;ffnet.");
}

function wartung_ankuendigung ()
{
  global $B_wartung_start, $B_wartung_ende;

  if (!$B_wartung_start)
    return;

  $stempel = time ();
  $diff = $B_wartung_start - $stempel;
  $dauer = ($B_wartung_ende - $B_wartung_start) / 60;
  
  if ($diff < 60)
  {
    if ($diff == 1)
      $start = 'einer Sekunde';
    else
      $start = "$diff Sekunden";
  }
  else
  {
    $diff = intval ($diff / 60);
    if ($diff == 1)
      $start = 'einer Minute';
    else
      $start = "$diff Minuten";
  }

  echo "<h1 align=\"center\">Wartungsarbeiten</h1>
  <h3 align=\"center\">Das Forum wird in $start f&uuml;r Wartungsarbeiten f&uuml;r etwa $dauer Minuten gesperrt werden.</h3>";
}
;?>

