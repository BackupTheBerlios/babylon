<?PHP;
include_once ("konf/konf.php");

$stempel = time ();

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
       $m = 'Minuten'

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

echo "<h1>Wartungsarbeiten</h1>
Das Forum ist f&uuml;r Wartungsarbeiten geschlossen.<p>
Es wird voraussichtlich in $geschlossen_bis wieder er&ouml;ffnet.";

;?>

